const mongoose = require('mongoose');

const scope = (req) => ({ tenant: req.tenantId, removed: false });
const ingest = async (req, res) => {
  const source = await mongoose.model('KnowledgeSource').findOneAndUpdate({ _id: req.params.id, ...scope(req) }, { status: 'queued' }, { new: true });
  if (!source) return res.status(404).json({ success: false, message: 'Knowledge source not found' });
  await mongoose.model('Job').create({ tenant: req.tenantId, createdBy: req.admin?._id, type: 'knowledge.ingest', payload: { source: source._id }, status: 'queued' });
  return res.status(200).json({ success: true, result: source, message: 'Knowledge ingestion queued' });
};
const search = async (req, res) => {
  const query = String(req.query.q || '').trim();
  const results = query ? await mongoose.model('KnowledgeChunk').find({ ...scope(req), text: { $regex: query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), $options: 'i' } }).limit(10).lean() : [];
  return res.status(200).json({ success: true, result: results.map((item) => ({ ...item, citation: { sourceId: item.source, documentId: item.document, location: `chunk:${item.sequence}` } })), message: 'Knowledge search completed' });
};
module.exports = { ingest, search };
