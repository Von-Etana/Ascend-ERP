const { buildEnterpriseOverviewReport } = require('@/services/platform/enterpriseWorkflows');

const overview = async (req, res) => {
  try {
    const result = await buildEnterpriseOverviewReport({ tenantId: req.tenantId });
    return res.status(200).json({
      success: true,
      result,
      message: 'Enterprise reports overview loaded',
    });
  } catch (error) {
    return res.status(error.status || 500).json({
      success: false,
      result: null,
      message: error.message || 'Unable to load enterprise reports overview',
    });
  }
};

module.exports = { overview };
