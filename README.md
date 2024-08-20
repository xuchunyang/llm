# 试用 Laravel Zero 写个 LLM CLI 工具

Laravel Zero 提供了类似 Laravel 的框架，但是用于 CLI 工具的开发。这里试用 Laravel Zero 写一个 LLM（Large Language Model）的
CLI 工具，用于调用豆包的大模型。

## 使用

```bash
➜  ~ ssh llm@xuchunyang.cn

   LLM  正在使用 API key: b79a39f7***, Base uri: ark.cn-beijing.volces.com/api/v3, Model: ep-20240820141803-2b9fb, 请在 ~/.llm/config.php 中修改配置


 ┌ 发消息 ──────────────────────────────────────────────────────┐
 │ 你是？                                                       │
 └──────────────────────────────────────────────────────────────┘

我是豆包，是字节跳动公司开发的人工智能。我可以回答各种问题并与你交流，很高兴为你服务！

 ┌ 发消息 ──────────────────────────────────────────────────────┐
 │ 用英文回答之前的问题                                         │
 └──────────────────────────────────────────────────────────────┘

I am Doubao, an AI developed by ByteDance. I can answer various questions and communicate with you. I am glad to serve you!

 ┌ 发消息 ──────────────────────────────────────────────────────┐
 │                                                              │
 └──────────────────────────────────────────────────────────────┘

再见
Connection to xuchunyang.cn closed.
```

## 配置

请在 `~/.llm/config.php` 中配置 API 信息，可以用兼容 OpenAI API 的大模型，下面以豆包的为例：

```php
return [
    'api_key' => 'xxx',
    'base_uri' => 'ark.cn-beijing.volces.com/api/v3',
    'model' => 'ep-20240820141803-2b9fb',
];
```

## 打包

Laravel Zero 提供了打包工具，可以将 CLI 工具打包成一个可执行文件，这样部署和使用都会更加方便。

```shell
php llm app:build -vvv
```

## 用到的包

- Laravel Zero, 类似的 Laravel 的 CLI 框架
- OpenAI PHP，用于调用兼容 OpenAI API 的大模型
- Laravel Prompt，用于交互式命令行
- Termwind, 在命令行中使用 Tailwind CSS
