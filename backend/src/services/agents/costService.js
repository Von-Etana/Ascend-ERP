const calculateCost = ({ usage = {}, provider = 'openai' }) => {
  const rates = { openai: [0.0000025, 0.00001], kimi: [0.000001, 0.000003], hermes: [0.000002, 0.000006] };
  const [inputRate, outputRate] = rates[provider] || rates.openai;
  const usd = (usage.inputTokens || 0) * inputRate + (usage.outputTokens || 0) * outputRate;
  const ngnRate = Number(process.env.AI_USD_NGN_RATE || 1600);
  return { amount: Number((usd * ngnRate).toFixed(2)), currency: 'NGN', usd: Number(usd.toFixed(6)) };
};

module.exports = { calculateCost };
