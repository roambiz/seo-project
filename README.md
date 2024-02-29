# SEO Project
Create a modular plugin for the SEO project, allowing the addition of modules as needed.

说明：该插件是为了方便B端独立站上产品、跑广告而制作，在实际使用中考虑了后续的扩展性，因为有给外包（雇员）分配权限的需求因此进行了如下设计，分类术语应该是由管理员进行创建，再根据帖子类型进行权限的分配，从而再统一的分类实现两组不同的权限。最开始只想要一个类似于Woo的[ Product ]文件夹的插件，但是市面上找到插件都太过臃肿，而且带了很烦人的广告，没找令我满意的，算了，干脆自己做一个。
## 下载快捷链接
> [Download 1.0.0](https://github.com/roambiz/seo-project/releases/tag/1.0.0)
> [Download 1.0.1](https://github.com/roambiz/seo-project/releases/tag/1.0.1)

=== SEO Project ===

## 默认模组
下面是简单的功能介绍：
### Module_CPT
+ 添加一个帖子类型:可以修改：1.icon；2.label；3.slug;
+ 添加帖子类型角色编辑权限;
+ 继承默认帖子类型的分类术语规则;
### Flush_CPT
+ 刷新重写规则;
+ 任何因为修改slug导致的页面404均可以使用该功能解决;
### Plugin Template
+ 方便添加后续的功能模块;

## 使用场景
一个项目集文件夹（Portfolio）：
* 用于快速启动项目的页面类型。方便添加产品、或生产相关的内容，适合用于制作生产相关的营销落地页。
* 独立的编辑权限分配。你可以分配相同的工作组给不同的人，例如相同的类目，根据帖子类型限制一个管理文章，一个管理产品上传。
* 比较适用于相对于简单的、没有很多动态数据需求的B端，主要用于产品展示和广告落地页。你也可以用于存储古腾堡模式的的作品集。
* 如果你觉得你的C端独立站也用得上，那么很高兴它能够帮助你，一般情况下它并不适合于需要调用很多动态字段的页面。

## 栏目页制作
`Quick Start 1.0.0` 为了极致追求站点轻量化，插件默认是不会将新帖子类型里面的帖子调用到分类存档页面的，因为用不到，当你需要制作栏目页时，使用下面的代码，根据需要使用页面（Page）来制作栏目页，只需要将分类的Slug和页面的Slug设置成一样的即可,类目页就会自动显示你自定义的页面的内容，不过注意，你可能还需要移除分类基础（Category Base）,否则它会出现两个重复的页面，不过你也可以使用301来解决该问题。

当然，如果你觉得使用Page制作栏目页（帖子存档）比较麻烦，可以下载`Add CPT To Post Query loop 1.1.0` ，该版本的分类页会调用帖子。
```
add_filter('request', function( array $query_vars ) {
    if ( is_admin() ) {
        return $query_vars;
    }

    // Add Custom Category Slug
    $targets = array(
        'my-category-slug-1',
        'my-category-slug-2',
        'my-category-slug-3',
    );

    if ( isset( $query_vars['category_name'] ) && in_array( $query_vars['category_name'], $targets ) ) {
        
        $pagename = $query_vars['category_name'];

        $query_vars = array( 'pagename' => "$pagename" );
    }

    return $query_vars;
} );
```
```
# ref: https://wordpress.org/documentation/article/settings-permalinks-screen/

RewriteRule ^category/(.+)$ http://www.YourDomain.com/$1 [R=301,L]
```

## 其他备注
>直接上传压缩包安装，后缀名必须是ZIP格式。

>键名为`seo-project`