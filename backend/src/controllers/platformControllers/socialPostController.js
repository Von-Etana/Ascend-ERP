const mongoose = require('mongoose');

const schedule = async (req, res) => {
  const SocialPost = mongoose.model('SocialPost');
  const post = await SocialPost.create({
    tenant: req.tenantId,
    createdBy: req.admin?._id,
    assignedTo: req.admin?._id,
    provider: req.body.provider || 'facebook',
    caption: req.body.caption,
    mediaUrl: req.body.mediaUrl,
    campaign: req.body.campaign,
    scheduledAt: req.body.scheduledAt,
    status: 'scheduled',
  });

  return res.status(200).json({
    success: true,
    result: post,
    message: 'Social post scheduled',
  });
};

module.exports = { schedule };
