<?php
    return [
        [
            'can' => [
                'banner-list','banner-create','banner-update','banner-delete',
                'category-list','category-create','category-update','category-delete',
                'user-list','user-create','user-update','user-delete',
                'role-list','role-create','role-update','role-delete',
                'permission-list','permission-create','permission-update','permission-delete',
                'product-list','product-create','product-update','product-delete',
                'order-list','order-update','order-delete',
                'detail-list','detail-update','detail-delete',
                'review-list','review-update','review-delete',
                'comment-list','comment-update','comment-delete',
                'stock-list','stock-create','stock-update','stock-delete',
                'catBlog-list','catBlog-create','catBlog-update','catBlog-delete',
                'blog-list','blog-create','blog-update','blog-delete'

            ],
            'name' => 'Trang chủ',
            'icon' => 'fa fa-dashboard',
            'route' => 'admin.index'
        ],[
            'can' => [
                'banner-list','banner-create','banner-update','banner-delete'
            ],
            'name' => 'Quản lý banner',
            'icon' => 'glyphicon glyphicon-picture',
            'route' => 'banner.index'

        ],
        [
            'can' => [
                'category-list','category-create','category-update','category-delete'
            ],
            'name' => 'Quản lý danh mục',
            'icon' => 'fa fa-align-justify',
            'route' => 'category.index'

        ],[
            'can' => [
                'user-list','user-create','user-update','user-delete'
            ],
            'name' => 'Quản lý tài khoản admin',
            'icon' => 'glyphicon glyphicon-user',
            'route' => 'user.index',
            'item' => [
                [
                    'can' => [
                        'user-list','user-create','user-update','user-delete'
                    ],
                    'name' => 'Quản lý tài khoản',
                    'route' => 'user.index'
                ],
                [
                    'can' => [
                        'role-list','role-create','role-update','role-delete'
                    ],
                    'name' => 'Quản lý chức danh',
                    'route' => 'role.index'
                ],
                [
                    'can' => [
                        'permission-list','permission-create','permission-update','permission-delete'
                    ],
                    'name' => 'Quản lý quyền',
                    'route' => 'permission.index'
                ]
            ]
        ],
        [
            'can' => [
                'product-list','product-create','product-update','product-delete'
            ],
            'name' => 'Quản lý sản phẩm',
            'icon' => 'glyphicon glyphicon-shopping-cart',
            'route' => 'product.index'

        ],
        [
            'can' => [
                'banner-list','banner-create','banner-update','banner-delete',
                'category-list','category-create','category-update','category-delete',
                'user-list','user-create','user-update','user-delete',
                'role-list','role-create','role-update','role-delete',
                'permission-list','permission-create','permission-update','permission-delete',
                'product-list','product-create','product-update','product-delete',
                'order-list','order-update','order-delete',
                'detail-list','detail-update','detail-delete',
                'review-list','review-update','review-delete',
                'comment-list','comment-update','comment-delete',
                'stock-list','stock-create','stock-update','stock-delete',
                'catBlog-list','catBlog-create','catBlog-update','catBlog-delete',
                'blog-list','blog-create','blog-update','blog-delete'

            ],
            'name' => 'Quản lý File',
            'icon' => 'glyphicon glyphicon-folder-open',
            'route' => 'file.index'

        ],
        [
            'can' => [
                'order-list','order-update','order-delete',
                'detail-list','detail-update','detail-delete'
            ],
            'name' => 'Quản lý đơn hàng',
            'icon' => 'glyphicon glyphicon-gift',
            'route' => 'order.index'

        ],
        [
            'can' => [
                'review-list','review-update','review-delete'
            ],
            'name' => 'Quản lý đánh giá',
            'icon' => 'glyphicon glyphicon-comment',
            'route' => 'review.index'

        ],[
            'can' => [
                'catBlog-list','catBlog-create','catBlog-update','catBlog-delete'
            ],
            'name' => 'Quản lý danh mục bài viết',
            'icon' => 'glyphicon glyphicon-th',
            'route' => 'categoryBlog.index'

        ],[
            'can' => [
                'blog-list','blog-create','blog-update','blog-delete'
            ],
            'name' => 'Quản lý bài viết',
            'icon' => 'glyphicon glyphicon-pencil',
            'route' => 'blog.index'

        ],[
            'can' => [
                'comment-list','comment-update','comment-delete'
            ],
            'name' => 'Quản lý bình luận',
            'icon' => 'glyphicon glyphicon-envelope',
            'route' => 'comment.index'

        ],[
            'can' => [
                'stock-list','stock-create','stock-update','stock-delete'
            ],
            'name' => 'Quản lý kho',
            'icon' => 'glyphicon glyphicon-folder-close',
            'route' => 'stocks.index'

        ]
    ];


?>
