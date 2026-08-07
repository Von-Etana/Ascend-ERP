const mongoose = require('mongoose');
const { createSocialPublisher } = require('@/services/agents/socialPublisher');
const { resolveProviderEnvForTenant } = require('@/services/integrations/accountService');

const scope = (req) => ({ tenant: req.tenantId, removed: false });
const calendar = async (req, res) => {
  const from = req.query.from ? new Date(req.query.from) : new Date(Date.now() - 30 * 86400000);
  const to = req.query.to ? new Date(req.query.to) : new Date(Date.now() + 90 * 86400000);
  const result = await mongoose.model('SocialPost').find({ ...scope(req), scheduledAt: { $gte: from, $lte: to } }).sort({ scheduledAt: 1 }).lean();
  return res.status(200).json({ success: true, result, message: 'Social calendar loaded' });
};
const preview = async (req, res) => {
  const channels = req.body.channels || [req.body.provider || 'facebook'];
  const result = channels.map((channel) => ({ channel, caption: req.body.channelVariants?.[channel]?.caption || req.body.caption || '', mediaUrl: req.body.channelVariants?.[channel]?.mediaUrl || req.body.mediaUrl, characterCount: String(req.body.channelVariants?.[channel]?.caption || req.body.caption || '').length }));
  return res.status(200).json({ success: true, result, message: 'Channel previews generated' });
};
const schedule = async (req, res) => {
  const post = await mongoose.model('SocialPost').create({ ...req.body, tenant: req.tenantId, createdBy: req.admin?._id, assignedTo: req.admin?._id, status: 'pending_approval', approvalStatus: 'pending' });
  return res.status(200).json({ success: true, result: post, message: 'Social post scheduled for approval' });
};
const publish = async (req, res) => {
  const post = await mongoose.model('SocialPost').findOne({ _id: req.params.id, ...scope(req), approvalStatus: 'approved' });
  if (!post) return res.status(409).json({ success: false, message: 'An approved social post is required' });
  post.status = 'publishing'; await post.save();
  const env = await resolveProviderEnvForTenant({ tenantId: req.tenantId });
  const publisher = createSocialPublisher(env); const results = [];
  for (const channel of post.channels?.length ? post.channels : [post.provider]) {
    try {
      const result = await publisher.publish(channel, { caption: post.channelVariants?.[channel]?.caption || post.caption, mediaUrl: post.mediaUrl });
      results.push(result); post.publicationAttempts.push({ provider: channel, status: result.disabled ? 'disabled' : 'published', attemptedAt: new Date(), providerPostId: result.providerPostId });
    } catch (error) { results.push({ provider: channel, error: error.message }); post.publicationAttempts.push({ provider: channel, status: 'failed', attemptedAt: new Date(), error: error.message }); }
  }
  post.status = results.some((item) => item.error) ? 'failed' : results.every((item) => item.disabled) ? 'scheduled' : 'published'; await post.save();
  return res.status(200).json({ success: true, result: { post, results }, message: 'Social publication processed' });
};
const metrics = async (req, res) => {
  const posts = await mongoose.model('SocialPost').find(scope(req)).lean();
  const result = { total: posts.length, scheduled: posts.filter((p) => p.status === 'scheduled').length, published: posts.filter((p) => p.status === 'published').length, failed: posts.filter((p) => p.status === 'failed').length, byChannel: {} };
  for (const post of posts) for (const channel of post.channels?.length ? post.channels : [post.provider]) result.byChannel[channel] = (result.byChannel[channel] || 0) + 1;
  return res.status(200).json({ success: true, result, message: 'Social metrics loaded' });
};
module.exports = { calendar, preview, schedule, publish, metrics };
