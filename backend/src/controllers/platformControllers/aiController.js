const mongoose = require('mongoose');
const path = require('path');
const fs = require('fs');
const { createProviderRegistryForTenant } = require('@/services/integrations/providerRegistry');

const contentTypePrompts = {
  newsletter: 'Write a newsletter for this brand and audience.',
  email: 'Write a marketing email with subject and body.',
  blog: 'Write a structured blog post.',
  caption: 'Write social media captions for Facebook and Instagram.',
  flyer_copy: 'Write concise flyer headline, subhead, body, and CTA copy.',
  script: 'Write a short promotional video or sales script.',
};

const generateContent = async (req, res) => {
  const { type = 'email', prompt = '', brandContext = {}, save = true } = req.body;
  const providers = await createProviderRegistryForTenant({ tenantId: req.tenantId });
  const result = await providers.kimi.request('generateContent', {
    instruction: contentTypePrompts[type] || contentTypePrompts.email,
    prompt,
    brandContext,
  });

  let asset = null;
  if (save && mongoose.models.ContentAsset) {
    asset = await mongoose.model('ContentAsset').create({
      tenant: req.tenantId,
      createdBy: req.admin?._id,
      assignedTo: req.admin?._id,
      title: `${type} draft`,
      type,
      prompt,
      content: result.content || result.message || `${contentTypePrompts[type] || ''}\n\n${prompt}`,
      provider: 'kimi',
      brandContext,
    });
  }

  return res.status(200).json({
    success: true,
    result: { provider: result, asset },
    message: 'Content generation request processed',
  });
};

const generateBrandAsset = async (req, res) => {
  const { prompt = '', brandContext = {}, save = true } = req.body;
  const providers = await createProviderRegistryForTenant({ tenantId: req.tenantId });
  const result = await providers.fal.request('generateBrandAsset', { prompt, brandContext });

  let asset = null;
  if (save && mongoose.models.ContentAsset) {
    asset = await mongoose.model('ContentAsset').create({
      tenant: req.tenantId,
      createdBy: req.admin?._id,
      assignedTo: req.admin?._id,
      title: 'Brand asset draft',
      type: 'brand_asset',
      prompt,
      content: result.message,
      provider: 'fal',
      brandContext,
    });
  }

  return res.status(200).json({
    success: true,
    result: { provider: result, asset },
    message: 'Brand asset generation request processed',
  });
};

const draftCampaign = async (req, res) => {
  req.body.type = 'newsletter';
  return generateContent(req, res);
};

/**
 * Compile an HTML template by replacing {{key}} placeholders with extracted field values.
 */
const compileTemplate = (html, fields) => {
  let compiled = html;
  Object.entries(fields).forEach(([key, value]) => {
    const safeValue = Array.isArray(value)
      ? value
          .map((item) =>
            typeof item === 'object'
              ? `<tr><td>${item.description || item.name || '-'}</td><td>${item.qty ?? 1}</td><td>${item.price ?? 0}</td><td>${(item.qty ?? 1) * (item.price ?? 0)}</td></tr>`
              : `<li>${item}</li>`
          )
          .join('')
      : String(value ?? '');
    compiled = compiled.replace(new RegExp(`\\{\\{${key}\\}\\}`, 'g'), safeValue);
  });
  return compiled;
};

/**
 * Build a sensible default HTML template if the stored template has no htmlContent.
 */
const buildDefaultHtml = (type, accentColor = '#1677ff', footerText = 'Thank you for your business.') => `
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<style>
  body { font-family: Arial, sans-serif; font-size: 13px; color: #1e293b; margin: 0; padding: 0; }
  .header { background: ${accentColor}; color: #fff; padding: 24px 32px; }
  .header h1 { margin: 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px; }
  .body { padding: 32px; }
  .meta { margin-bottom: 24px; display: flex; justify-content: space-between; }
  .meta-block h3 { margin: 0 0 4px; font-size: 11px; color: #64748b; text-transform: uppercase; }
  table { width: 100%; border-collapse: collapse; margin-top: 16px; }
  th { background: #f1f5f9; padding: 8px 12px; text-align: left; font-size: 11px; color: #64748b; text-transform: uppercase; }
  td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; }
  .total-row td { font-weight: bold; border-top: 2px solid ${accentColor}; font-size: 15px; color: ${accentColor}; }
  .notes { margin-top: 24px; padding: 14px; background: #f8fafc; border-radius: 6px; font-size: 12px; color: #475569; }
  .footer { padding: 16px 32px; border-top: 1px solid #e2e8f0; font-size: 11px; color: #94a3b8; text-align: center; }
</style>
</head>
<body>
<div class="header">
  <h1>${type}</h1>
</div>
<div class="body">
  <div class="meta">
    <div class="meta-block">
      <h3>Bill To</h3>
      <strong>{{clientName}}</strong><br/>
      {{clientEmail}}
    </div>
    <div class="meta-block" style="text-align:right;">
      <h3>Document Date</h3>
      {{date}}<br/>
      <h3 style="margin-top:8px;">Document Number</h3>
      {{documentNumber}}
    </div>
  </div>

  <table>
    <thead>
      <tr><th>Description</th><th>Qty</th><th>Unit Price</th><th>Amount</th></tr>
    </thead>
    <tbody>
      {{items}}
    </tbody>
    <tfoot>
      <tr class="total-row"><td colspan="3">Total</td><td>{{currency}} {{total}}</td></tr>
    </tfoot>
  </table>

  {{#if notes}}
  <div class="notes"><strong>Notes:</strong> {{notes}}</div>
  {{/if}}
</div>
<div class="footer">${footerText}</div>
</body>
</html>`;

/**
 * Use the AI to parse a natural-language prompt into structured document fields.
 * Falls back to a deterministic extractor when AI is unavailable.
 */
const extractFieldsFromPrompt = async (prompt, templateFields, providers) => {
  const instruction = `You are a document data extractor. Parse the following instruction into a JSON object with these keys: ${templateFields.join(', ')}.
For "items", produce an array of objects with keys: description, qty, price.
For "date", use today's date in YYYY-MM-DD format if not specified.
For "documentNumber", generate a short unique reference like INV-2026-001.
Return ONLY valid JSON, no explanations.`;

  try {
    const result = await providers.kimi.request('generateContent', { instruction, prompt, brandContext: {} });
    const raw = (result.content || result.message || '{}').replace(/```json|```/g, '').trim();
    return JSON.parse(raw);
  } catch {
    // Deterministic fallback — extract obvious numbers and emails
    const emailMatch = prompt.match(/[\w.-]+@[\w.-]+\.\w+/);
    const amountMatch = prompt.match(/[\d,]+(\.\d{1,2})?/);
    const total = amountMatch ? parseFloat(amountMatch[0].replace(',', '')) : 0;
    return {
      clientName: 'Client',
      clientEmail: emailMatch ? emailMatch[0] : '',
      date: new Date().toISOString().slice(0, 10),
      documentNumber: `DOC-${Date.now().toString().slice(-6)}`,
      currency: 'NGN',
      total,
      items: [{ description: prompt.slice(0, 60), qty: 1, price: total }],
      notes: '',
    };
  }
};

/**
 * POST /ai/document/generate-from-template
 * Body: { templateId, prompt, recipientEmail?, sendEmail? }
 */
const generateFromTemplate = async (req, res) => {
  const { templateId, prompt = '', recipientEmail, sendEmail = false } = req.body;

  if (!prompt.trim()) {
    return res.status(400).json({ success: false, message: 'A prompt describing the document is required.' });
  }

  const providers = await createProviderRegistryForTenant({ tenantId: req.tenantId });

  // Load template or use built-in default
  let template = null;
  if (templateId && mongoose.models.DocumentTemplate) {
    template = await mongoose.model('DocumentTemplate').findOne({ _id: templateId, tenant: req.tenantId });
  }

  const templateType = template?.type || 'invoice';
  const accentColor = template?.accentColor || '#1677ff';
  const footerText = template?.footerText || 'Thank you for your business.';
  const expectedFields = template?.fields || ['clientName', 'clientEmail', 'total', 'currency', 'date', 'items', 'notes', 'documentNumber'];

  // AI-powered field extraction
  const extractedFields = await extractFieldsFromPrompt(prompt, expectedFields, providers);

  // Compile HTML
  const baseHtml = template?.htmlContent || buildDefaultHtml(templateType, accentColor, footerText);
  const compiledHtml = compileTemplate(baseHtml, extractedFields);

  // Generate PDF via html-pdf if available, otherwise store HTML reference
  let pdfPath = null;
  let pdfFileName = null;
  try {
    const pdf = require('html-pdf');
    const publicDir = path.join(process.cwd(), 'public', 'generated_docs');
    if (!fs.existsSync(publicDir)) fs.mkdirSync(publicDir, { recursive: true });
    pdfFileName = `doc-${Date.now()}.pdf`;
    pdfPath = path.join(publicDir, pdfFileName);
    await new Promise((resolve, reject) =>
      pdf.create(compiledHtml, { format: 'A4' }).toFile(pdfPath, (err) => (err ? reject(err) : resolve()))
    );
  } catch {
    // html-pdf unavailable; store compiled HTML as content reference
    pdfFileName = null;
  }

  // Send email if requested and provider is configured
  let emailResult = null;
  const targetEmail = recipientEmail || extractedFields.clientEmail;
  if (sendEmail && targetEmail && providers.resend?.sendEmail) {
    try {
      emailResult = await providers.resend.sendEmail({
        to: targetEmail,
        subject: `Your ${templateType} is ready`,
        html: compiledHtml,
      });
    } catch (emailErr) {
      emailResult = { error: emailErr.message };
    }
  }

  // Save a record in ContentAsset for audit trail
  let asset = null;
  if (mongoose.models.ContentAsset) {
    asset = await mongoose.model('ContentAsset').create({
      tenant: req.tenantId,
      createdBy: req.admin?._id,
      assignedTo: req.admin?._id,
      title: `${templateType} — ${extractedFields.clientName || 'Client'} — ${extractedFields.date || new Date().toISOString().slice(0, 10)}`,
      type: templateType,
      prompt,
      content: compiledHtml,
      provider: 'kimi',
      brandContext: { documentType: templateType },
    });
  }

  return res.status(200).json({
    success: true,
    result: {
      extractedFields,
      compiledHtml,
      pdfFileName,
      emailResult,
      asset,
    },
    message: `${templateType} generated successfully${sendEmail && targetEmail ? ' and dispatched via email' : ''}.`,
  });
};

module.exports = { generateContent, generateBrandAsset, draftCampaign, generateFromTemplate };
