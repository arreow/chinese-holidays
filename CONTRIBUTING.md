# 贡献指南

感谢您考虑为本项目做出贡献！

## 提交流程

1. Fork 本仓库
2. 创建您的特性分支 (`git checkout -b feature/AmazingFeature`)
3. 提交您的更改 (`git commit -m 'Add some AmazingFeature'`)
4. 将您的更改推送到分支 (`git push origin feature/AmazingFeature`)
5. 开启一个 Pull Request

## 数据更新贡献

当每年国务院发布新的放假安排时，我们非常欢迎社区提交更新：

1. 在 `data` 目录下创建或更新对应年份的 JSON 文件，如 `2025.json`
2. 确保 `isOffDay: true` 代表休息的节假日，`isOffDay: false` 代表周末调休上班的日子
3. 确保 JSON 格式合法（请勿包含尾部逗号等非标准格式）
4. 运行 `composer test` 确保未破坏现有测试

## 代码规范

本项目遵循 PSR-12 代码规范。在提交代码之前，请确保您的代码风格符合规范，并且所有单元测试都能顺利通过。
