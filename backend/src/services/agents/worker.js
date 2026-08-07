const mongoose = require('mongoose');
const { processAgentRun } = require('./runtime');

const processDueAgentJobs = async ({ limit = 5 } = {}) => {
  const results = [];
  for (let index = 0; index < limit; index += 1) {
    const job = await mongoose.model('Job').findOneAndUpdate(
      { type: { $in: ['agent.run', 'agent.resume'] }, status: 'queued', runAfter: { $lte: new Date() } },
      { $set: { status: 'running', updated: new Date() }, $inc: { attempts: 1 } },
      { new: true, sort: { runAfter: 1, created: 1 } }
    );
    if (!job) break;
    try {
      const run = await processAgentRun({ runId: job.payload.run });
      job.status = run.status === 'needs_approval' ? 'waiting_approval' : 'succeeded';
      await job.save(); results.push({ job, run });
    } catch (error) {
      job.status = 'failed'; job.lastError = error.message; await job.save();
      await mongoose.model('AgentRun').findByIdAndUpdate(job.payload.run, { status: 'failed', error: error.message, completedAt: new Date() });
      results.push({ job, error });
    }
  }
  return results;
};

const startAgentWorker = ({ enabled = process.env.AGENT_WORKER_ENABLED !== 'false', intervalMs = Number(process.env.AGENT_WORKER_INTERVAL_MS || 15000), limit = Number(process.env.AGENT_WORKER_LIMIT || 5) } = {}) => {
  if (!enabled) return null;
  const tick = () => processDueAgentJobs({ limit }).catch((error) => console.error(`Agent worker error: ${error.message}`));
  const timer = setInterval(tick, intervalMs); timer.unref?.(); tick(); return timer;
};

module.exports = { processDueAgentJobs, startAgentWorker };
