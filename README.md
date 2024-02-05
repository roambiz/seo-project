# SEO Project
Create a modular plugin for the SEO project, allowing the addition of modules as needed.

说明：该插件是为了方便B端独立站上产品、跑广告而制作，在实际使用中考虑了后续的扩展性，因为有给外包（雇员）分配权限的需求因此进行了如下设计，分类术语应该是由管理员进行创建，再根据帖子类型进行权限的分配，从而再统一的分类实现两组不同的权限。

=== SEO Project ===

## 默认模组
下面是简单的功能介绍：
### Module_CPT
>+添加一个帖子类型:可以修改：1.icon；2.label；3.slug;
>+添加帖子类型角色编辑权限;
>+继承默认帖子类型的分类术语规则;
### Flush_CPT
>+刷新重写规则;
### Plugin Template
>+方便添加后续的功能模块;

## 使用场景
一个项目集文件夹（Portfolio）：
* 用于快速启动项目的页面类型。方便添加产品、或生产相关的内容，适合用于制作生产相关的营销落地页。
* 独立的编辑权限分配。你可以分配相同的工作组给不同的人，例如相同的类目，根据帖子类型限制一个管理文章，一个管理产品上传。
* 比较适用于相对于简单的、没有很多动态数据需求的B端，主要用于产品展示和广告落地页。你也可以用于存储古腾堡模式的的作品集。


## 其他备注
- 直接上传压缩包安装，后缀名必须是ZIP格式。
- 键名为`seo-project`