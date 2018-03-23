<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->identity->username ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => '主页', 'icon' => 'home', 'url' => '/'],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
//                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => '登录', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
//                    [
//                        'label' => 'Some tools',
//                        'icon' => 'share',
//                        'url' => '#',
//                        'items' => [
//                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
//                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
//                            [
//                                'label' => 'Level One',
//                                'icon' => 'circle-o',
//                                'url' => '#',
//                                'items' => [
//                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
//                                    [
//                                        'label' => 'Level Two',
//                                        'icon' => 'circle-o',
//                                        'url' => '#',
//                                        'items' => [
//                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
//                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
//                                        ],
//                                    ],
//                                ],
//                            ],
//                        ],
//                    ],
                    [
                        'label' => '项目管理',
                        'icon' => 'th-list',
                        'items' => [
                            ['label' => '项目列表', 'url' => ['event/index'], 'icon' => 'minus']
                        ]
                    ],
                    [
                        'label' => '财务管理',
                        'icon' => 'bitcoin',
                        'items' => [
                            ['label' => '购票列表', 'url' => ['account/index'], 'icon' => 'minus']
                        ]
                    ],
                    [
                        'label' => '售票管理',
                        'icon' => 'ticket',
                        'items' => [
                            ['label' => '在售列表', 'url' => ['sale/index'], 'icon' => 'minus']
                        ]
                    ],
                    [
                        'label' => '权限管理',
                        'icon' => 'users',
                        'items' => [
                            ['label' => '用户管理', 'url' => ['sale/index'], 'icon' => 'minus'],
                            ['label' => '角色管理', 'url' => ['sale/index'], 'icon' => 'minus'],
                            ['label' => '权限关联', 'url' => ['sale/index'], 'icon' => 'minus'],
                        ]
                    ]
                ],
            ]
        ) ?>

    </section>

</aside>
