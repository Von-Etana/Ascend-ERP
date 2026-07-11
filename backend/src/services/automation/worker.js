const { processDueAutomationJobs } = require('./runner');
const { startAgentWorker } = require('@/services/agents/worker');

const startAutomationWorker = ({
  enabled = process.env.AUTOMATION_WORKER_ENABLED === 'true',
  intervalMs = Number(process.env.AUTOMATION_WORKER_INTERVAL_MS || 30000),
  limit = Number(process.env.AUTOMATION_WORKER_LIMIT || 10),
} = {}) => {
  if (!enabled) return null;

  const tick = async () => {
    try {
      await processDueAutomationJobs({ limit });
    } catch (error) {
      console.error(`Automation worker error: ${error.message}`);
    }
  };

  const timer = setInterval(tick, intervalMs);
  timer.unref?.();
  tick();
  return timer;
};

module.exports = { startAutomationWorker };
module.exports.startAgentWorker = startAgentWorker;
