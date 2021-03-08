import Vue from 'vue'
import Router from 'vue-router'

// Containers
import Container from './containers/Container';

// Views
import Dashboard from './views/Dashboard';

// Tasks
import Tasks from "./views/Tasks";

// Jobs
import JobsView from "./views/Jobs";
import JobView from "./views/Job";

// Clients
import Clients from "./views/Clients";
import Client from "./views/Client";

// Authentication
import Login from './views/AuthLogin';

/*
 * EXAMPLES
 */
import Colors from './components/examples/theme/Colors';
import Typography from './components/examples/theme/Typography';
import Charts from './components/examples/charts/Charts';
import Widgets from './components/examples/widgets/Widgets';
import Cards from './components/examples/base/Cards';
import Forms from './components/examples/base/Forms';
import Switches from './components/examples/base/Switches';
import Tables from './components/examples/base/Tables';
import Tabs from './components/examples/base/Tabs';
import Breadcrumbs from './components/examples/base/Breadcrumbs';
import Carousels from './components/examples/base/Carousels';
import Collapses from './components/examples/base/Collapses';
import Jumbotrons from './components/examples/base/Jumbotrons';
import ListGroups from './components/examples/base/ListGroups';
import Navs from './components/examples/base/Navs';
import Navbars from './components/examples/base/Navbars';
import Paginations from './components/examples/base/Paginations';
import Popovers from './components/examples/base/Popovers';
import ProgressBars from './components/examples/base/ProgressBars';
import Tooltips from './components/examples/base/Tooltips';
import StandardButtons from './components/examples/buttons/StandardButtons';
import ButtonGroups from './components/examples/buttons/ButtonGroups';
import Dropdowns from './components/examples/buttons/Dropdowns';
import BrandButtons from './components/examples/buttons/BrandButtons';
import CoreUIIcons from './components/examples/icons/CoreUIIcons';
import Brands from './components/examples/icons/Brands';
import Flags from './components/examples/icons/Flags';
import Alerts from './components/examples/notifications/Alerts';
import Badges from './components/examples/notifications/Badges';
import Modals from './components/examples/notifications/Modals';
import Page404 from './components/examples/pages/Page404';
import Page500 from './components/examples/pages/Page500';
import Register from './components/examples/pages/Register';
import Users from './components/examples/users/Users';
import User from './components/examples/users/User';
import EditUser from './components/examples/users/EditUser';
import Notes from './components/examples/notes/Notes';
import Note from './components/examples/notes/Note';
import EditNote from './components/examples/notes/EditNote';
import CreateNote from './components/examples/notes/CreateNote';
import Roles from './components/examples/roles/Roles';
import Role from './components/examples/roles/Role';
import EditRole from './components/examples/roles/EditRole';
import CreateRole from './components/examples/roles/CreateRole';
import Breads from './components/examples/bread/Breads';
import Bread from './components/examples/bread/Bread';
import EditBread from './components/examples/bread/EditBread';
import CreateBread from './components/examples/bread/CreateBread';
import DeleteBread from './components/examples/bread/DeleteBread';
import Resources from './components/examples/resources/Resources';
import CreateResource from './components/examples/resources/CreateResources';
import Resource from './components/examples/resources/Resource';
import EditResource from './components/examples/resources/EditResource';
import DeleteResource from './components/examples/resources/DeleteResource';
import Emails from './components/examples/email/Emails';
import CreateEmail from './components/examples/email/CreateEmail';
import EditEmail from './components/examples/email/EditEmail';
import ShowEmail from './components/examples/email/ShowEmail';
import SendEmail from './components/examples/email/SendEmail';
import Menus from './components/examples/menu/MenuIndex';
import CreateMenu from './components/examples/menu/CreateMenu';
import EditMenu from './components/examples/menu/EditMenu';
import DeleteMenu from './components/examples/menu/DeleteMenu';
import MenuElements from './components/examples/menuElements/ElementsIndex';
import CreateMenuElement from './components/examples/menuElements/CreateMenuElement';
import EditMenuElement from './components/examples/menuElements/EditMenuElement';
import ShowMenuElement from './components/examples/menuElements/ShowMenuElement';
import DeleteMenuElement from './components/examples/menuElements/DeleteMenuElement';
import Media from './components/examples/media/Media';

Vue.use(Router)

let router = new Router({
    mode: 'history', // https://router.vuejs.org/coreui/#mode
    linkActiveClass: 'active',
    scrollBehavior: () => ({y: 0}),
    routes: configRoutes()
})


router.beforeEach((to, from, next) => {
    let roles = localStorage.getItem("roles");
    if (roles != null) {
        roles = roles.split(',')
    }
    if (to.matched.some(record => record.meta.requiresAdmin)) {
        if (roles != null && roles.indexOf('admin') >= 0) {
            next()
        } else {
            next({
                path: '/login',
                params: {nextUrl: to.fullPath}
            })
        }
    } else if (to.matched.some(record => record.meta.requiresUser)) {
        if (roles != null && roles.indexOf('user') >= 0) {
            next()
        } else {
            next({
                path: '/login',
                params: {nextUrl: to.fullPath}
            })
        }
    } else {
        next()
    }
})

export default router

function configRoutes() {
    return [
        {
            path: '/',
            component: Container,
            children: [
                {
                    path: '',
                    name: 'Today',
                    component: Dashboard,
                    meta: {
                        requiresUser: true,
                        pageTitle: "Today"
                    }
                },
                {
                    path: 'tasks',
                    name: 'Tasks',
                    meta: {
                        requiresUser: true,
                        pageTitle: "Tasks",
                        links: [
                            {name: 'My Tasks', to: 'Tasks', active: true},
                            {name: 'All Tasks', to: 'TasksAll'},
                            {name: 'Completed', to: 'TasksCompleted'}
                        ],
                        linksRight: [
                            {
                                name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                to: 'TasksDeleted'
                            }
                        ],
                        dropdown: [
                            {name: 'List view', to: 'Tasks', active: true},
                            {name: 'Board view', to: 'TasksBoard'},
                            {name: 'Calendar view', to: 'TasksCalendar'}
                        ]
                    },
                    component: Tasks,
                    children: [
                        {
                            path: 'all',
                            name: 'TasksAll',
                            component: Tasks,
                            meta: {
                                requiresUser: true,
                                pageTitle: "Tasks",
                                links: [
                                    {name: 'My Tasks', to: 'Tasks'},
                                    {name: 'All Tasks', to: 'TasksAll', active: true},
                                    {name: 'Completed', to: 'TasksCompleted'}
                                ],
                                linksRight: [
                                    {
                                        name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                        to: 'TasksDeleted'
                                    }
                                ],
                                dropdown: [
                                    {name: 'List view', to: 'Tasks', active: true},
                                    {name: 'Board view', to: 'TasksBoard'},
                                    {name: 'Calendar view', to: 'TasksCalendar'}
                                ]
                            }
                        },
                        {
                            path: 'completed',
                            name: 'TasksCompleted',
                            component: Tasks,
                            meta: {
                                requiresUser: true,
                                pageTitle: "Tasks",
                                links: [
                                    {name: 'My Tasks', to: 'Tasks'},
                                    {name: 'All Tasks', to: 'TasksAll'},
                                    {name: 'Completed', to: 'TasksCompleted', active: true}
                                ],
                                linksRight: [
                                    {
                                        name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                        to: 'TasksDeleted'
                                    }
                                ],
                                dropdown: [
                                    {name: 'List view', to: 'Tasks', active: true},
                                    {name: 'Board view', to: 'TasksBoard'},
                                    {name: 'Calendar view', to: 'TasksCalendar'}
                                ]
                            }
                        },
                        {
                            path: 'deleted',
                            name: 'TasksDeleted',
                            component: Tasks,
                            meta: {
                                requiresUser: true,
                                pageTitle: "Tasks",
                                links: [
                                    {name: 'My Tasks', to: 'Tasks'},
                                    {name: 'All Tasks', to: 'TasksAll'},
                                    {name: 'Completed', to: 'TasksCompleted'}
                                ],
                                linksRight: [
                                    {
                                        name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                        to: 'TasksDeleted',
                                        active: true
                                    }
                                ],
                                dropdown: [
                                    {name: 'List view', to: 'Tasks', active: true},
                                    {name: 'Board view', to: 'TasksBoard'},
                                    {name: 'Calendar view', to: 'TasksCalendar'}
                                ]
                            }
                        },
                        {
                            path: 'board',
                            name: 'TasksBoard',
                            component: Tasks,
                            meta: {
                                requiresUser: true,
                                pageTitle: "Tasks",
                                links: [
                                    {name: 'My Tasks', to: 'TasksBoard', active: true},
                                    {name: 'All Tasks', to: 'TasksBoardAll'},
                                    {name: 'Completed', to: 'TasksBoardCompleted'}
                                ],
                                linksRight: [
                                    {
                                        name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                        to: 'TasksBoardDeleted'
                                    }
                                ],
                                dropdown: [
                                    {name: 'List view', to: 'Tasks'},
                                    {name: 'Board view', to: 'TasksBoard', active: true},
                                    {name: 'Calendar view', to: 'TasksCalendar'}
                                ]
                            },
                            children: [
                                {
                                    path: 'all',
                                    name: 'TasksBoardAll',
                                    component: Tasks,
                                    meta: {
                                        requiresUser: true,
                                        pageTitle: "Tasks",
                                        links: [
                                            {name: 'My Tasks', to: 'TasksBoard'},
                                            {name: 'All Tasks', to: 'TasksBoardAll', active: true},
                                            {name: 'Completed', to: 'TasksBoardCompleted'}
                                        ],
                                        linksRight: [
                                            {
                                                name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                                to: 'TasksBoardDeleted'
                                            }
                                        ],
                                        dropdown: [
                                            {name: 'List view', to: 'Tasks'},
                                            {name: 'Board view', to: 'TasksBoard', active: true},
                                            {name: 'Calendar view', to: 'TasksCalendar'}
                                        ]
                                    }
                                },
                                {
                                    path: 'completed',
                                    name: 'TasksBoardCompleted',
                                    component: Tasks,
                                    meta: {
                                        requiresUser: true,
                                        pageTitle: "Tasks",
                                        links: [
                                            {name: 'My Tasks', to: 'TasksBoard'},
                                            {name: 'All Tasks', to: 'TasksBoardAll'},
                                            {name: 'Completed', to: 'TasksBoardCompleted', active: true}
                                        ],
                                        linksRight: [
                                            {
                                                name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                                to: 'TasksBoardDeleted'
                                            }
                                        ],
                                        dropdown: [
                                            {name: 'List view', to: 'Tasks'},
                                            {name: 'Board view', to: 'TasksBoard', active: true},
                                            {name: 'Calendar view', to: 'TasksCalendar'}
                                        ]
                                    }
                                },
                                {
                                    path: 'deleted',
                                    name: 'TasksBoardDeleted',
                                    component: Tasks,
                                    meta: {
                                        requiresUser: true,
                                        pageTitle: "Tasks",
                                        links: [
                                            {name: 'My Tasks', to: 'TasksBoard'},
                                            {name: 'All Tasks', to: 'TasksBoardAll'},
                                            {name: 'Completed', to: 'TasksBoardCompleted'}
                                        ],
                                        linksRight: [
                                            {
                                                name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                                to: 'TasksBoardDeleted',
                                                active: true
                                            }
                                        ],
                                        dropdown: [
                                            {name: 'List view', to: 'Tasks'},
                                            {name: 'Board view', to: 'TasksBoard', active: true},
                                            {name: 'Calendar view', to: 'TasksCalendar'}
                                        ]
                                    }
                                },
                            ]
                        },
                        {
                            path: 'calendar',
                            name: 'TasksCalendar',
                            component: Tasks,
                            meta: {
                                requiresUser: true,
                                pageTitle: "Tasks",
                                links: [
                                    {name: 'My Tasks', to: 'TasksCalendar', active: true},
                                    {name: 'All Tasks', to: 'TasksCalendarAll'},
                                    {name: 'Completed', to: 'TasksCalendarCompleted'}
                                ],
                                linksRight: [
                                    {
                                        name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                        to: 'TasksCalendarDeleted'
                                    }
                                ],
                                dropdown: [
                                    {name: 'List view', to: 'Tasks'},
                                    {name: 'Board view', to: 'TasksBoard'},
                                    {name: 'Calendar view', to: 'TasksCalendar', active: true}
                                ]
                            },
                            children: [
                                {
                                    path: 'all',
                                    name: 'TasksCalendarAll',
                                    component: Tasks,
                                    meta: {
                                        requiresUser: true,
                                        pageTitle: "Tasks",
                                        links: [
                                            {name: 'My Tasks', to: 'TasksCalendar'},
                                            {name: 'All Tasks', to: 'TasksCalendarAll', active: true},
                                            {name: 'Completed', to: 'TasksCalendarCompleted'}
                                        ],
                                        linksRight: [
                                            {
                                                name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                                to: 'TasksCalendarDeleted'
                                            }
                                        ],
                                        dropdown: [
                                            {name: 'List view', to: 'Tasks'},
                                            {name: 'Board view', to: 'TasksBoard'},
                                            {name: 'Calendar view', to: 'TasksCalendar', active: true}
                                        ]
                                    }
                                },
                                {
                                    path: 'completed',
                                    name: 'TasksCalendarCompleted',
                                    component: Tasks,
                                    meta: {
                                        requiresUser: true,
                                        pageTitle: "Tasks",
                                        links: [
                                            {name: 'My Tasks', to: 'TasksCalendar'},
                                            {name: 'All Tasks', to: 'TasksCalendarAll'},
                                            {name: 'Completed', to: 'TasksCalendarCompleted', active: true}
                                        ],
                                        linksRight: [
                                            {
                                                name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                                to: 'TasksCalendarDeleted'
                                            }
                                        ],
                                        dropdown: [
                                            {name: 'List view', to: 'Tasks'},
                                            {name: 'Board view', to: 'TasksBoard'},
                                            {name: 'Calendar view', to: 'TasksCalendar', active: true}
                                        ]
                                    }
                                },
                                {
                                    path: 'deleted',
                                    name: 'TasksCalendarDeleted',
                                    component: Tasks,
                                    meta: {
                                        requiresUser: true,
                                        pageTitle: "Tasks",
                                        links: [
                                            {name: 'My Tasks', to: 'TasksCalendar'},
                                            {name: 'All Tasks', to: 'TasksCalendarAll'},
                                            {name: 'Completed', to: 'TasksCalendarCompleted'}
                                        ],
                                        linksRight: [
                                            {
                                                name: '<span class="d-none d-sm-flex">Recently Deleted</span><span class="d-flex d-sm-none h4 m-0"><i class="cil-trash"></i></span>',
                                                to: 'TasksCalendarDeleted',
                                                active: true
                                            }
                                        ],
                                        dropdown: [
                                            {name: 'List view', to: 'Tasks'},
                                            {name: 'Board view', to: 'TasksBoard'},
                                            {name: 'Calendar view', to: 'TasksCalendar', active: true}
                                        ]
                                    }
                                },
                            ]
                        }
                    ]
                },
                {
                    path: 'jobs',
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            name: 'Jobs',
                            component: JobsView,
                            meta: {
                                requiresUser: true,
                                pageTitle: "Jobs",
                                links: [
                                    {name: 'Active', href: '/jobs', active: true},
                                    {name: 'Archived', href: '/jobs/archived'}
                                ]
                            },

                        },
                        {
                            path: 'archived',
                            name: 'JobsArchived',
                            component: JobsView,
                            meta: {
                                requiresUser: true,
                                pageTitle: "Archived Jobs",
                                links: [
                                    {name: 'Active', href: '/jobs'},
                                    {name: 'Archived', href: '/jobs/archived', active: true}
                                ]
                            }
                        },
                        {
                            path: ':jobId',
                            name: 'Job',
                            component: JobView,
                            meta: {
                                requiresUser: true,
                                pageTitle: "Job Overview",
                                links: [
                                    {name: 'Overview', to: "Job", active: true},
                                    {name: 'Tasks', to: "JobTasks"},
                                    {name: 'Gantt', to: "JobGantt"}
                                ],
                                linksRight: [
                                    {name: '<i clAss="cil-settings"></i>', to: 'JobEdit'}
                                ]
                            },
                            children: [
                                {
                                    path: 'tasks',
                                    name: 'JobTasks',
                                    component: JobView,
                                    meta: {
                                        requiresUser: true,
                                        pageTitle: "Job Tasks",
                                        links: [
                                            {name: 'Overview', to: "Job"},
                                            {name: 'Tasks', to: "JobTasks", active: true},
                                            {name: 'Gantt', to: "JobGantt"}
                                        ],
                                        linksRight: [
                                            {name: '<i class="cil-settings"></i>', to: 'JobEdit'}
                                        ]
                                    }
                                },
                                {
                                    path: 'gantt',
                                    name: 'JobGantt',
                                    component: JobView,
                                    meta: {
                                        requiresUser: true,
                                        pageTitle: "Job Gantt",
                                        links: [
                                            {name: 'Overview', to: "Job"},
                                            {name: 'Tasks', to: "JobTasks"},
                                            {name: 'Gantt', to: "JobGantt", active: true}
                                        ],
                                        linksRight: [
                                            {name: '<i class="cil-settings"></i>', to: 'JobEdit'}
                                        ]
                                    }
                                },
                                {
                                    path: 'edit',
                                    name: 'JobEdit',
                                    component: JobView,
                                    meta: {
                                        requiresUser: true,
                                        pageTitle: "Edit Job",
                                        links: [
                                            {name: 'Settings', to: "JobEdit", active: true},
                                            {name: 'Statuses', to: "JobEditStatuses"},
                                            {name: 'Billing', to: "JobEditBilling"}
                                        ],
                                        linksRight: [
                                            {name: 'Back to Overview', to: 'Job'}
                                        ]
                                    },
                                    children: [
                                        {
                                            path: 'statuses',
                                            name: 'JobEditStatuses',
                                            component: JobView,
                                            meta: {
                                                requiresUser: true,
                                                pageTitle: "Edit Job Statuses",
                                                links: [
                                                    {name: 'Settings', to: "JobEdit"},
                                                    {name: 'Statuses', to: "JobEditStatuses", active: true},
                                                    {name: 'Billing', to: "JobEditBilling"}
                                                ],
                                                linksRight: [
                                                    {name: 'Back to Overview', to: 'Job'}
                                                ]
                                            }
                                        },
                                        {
                                            path: 'billing',
                                            name: 'JobEditBilling',
                                            component: JobView,
                                            meta: {
                                                requiresUser: true,
                                                pageTitle: "Edit Billing Settings",
                                                links: [
                                                    {name: 'Settings', to: "JobEdit"},
                                                    {name: 'Statuses', to: "JobEditStatuses"},
                                                    {name: 'Billing', to: "JobEditBilling", active: true}
                                                ],
                                                linksRight: [
                                                    {name: 'Back to Overview', to: 'Job'}
                                                ]
                                            }
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                {
                    path: 'clients',
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            name: 'Clients',
                            meta: {
                                requiresUser: true,
                                pageTitle: "Clients",
                                links: [
                                    {name: 'Active', href: '/clients', active: true},
                                    {name: 'Archived', href: '/clients/archived'}
                                ]
                            },
                            component: Clients,
                        },
                        {
                            path: 'archived',
                            name: 'ClientsArchived',
                            component: Clients,
                            meta: {
                                requiresUser: true,
                                pageTitle: "Clients",
                                links: [
                                    {name: 'Active', href: '/clients'},
                                    {name: 'Archived', href: '/clients/archived', active: true}
                                ]
                            }
                        },
                        {
                            path: ':clientId',
                            name: 'Client',
                            component: Client,
                            meta: {
                                requiresUser: true,
                                pageTitle: "View Client",
                                links: [
                                    {name: 'Active', href: '/clients'},
                                    {name: 'Archived', href: '/clients/archived'}
                                ]
                            }
                        }
                    ]
                }
            ]
        },
        {
            path: '/login',
            name: 'Login',
            component: Login
        },
        {
            path: '/register',
            name: 'Register',
            component: Register
        },
        {
            path: '*',
            name: '404',
            component: Page404
        }
    ]
}

function configRoutesExample() {
    return [
        {
            path: '/',
            name: 'Home',
            component: Container,
            children: [
                {
                    path: 'media',
                    name: 'Media',
                    component: Media,
                    meta: {
                        requiresUser: true
                    }
                },
                {
                    path: '',
                    name: 'Dashboard',
                    component: Dashboard,
                    meta: {
                        requiresUser: true
                    }
                },
                {
                    path: 'colors',
                    name: 'Colors',
                    component: Colors,
                    meta: {
                        requiresUser: true
                    }
                },
                {
                    path: 'typography',
                    name: 'Typography',
                    component: Typography,
                    meta: {
                        requiresUser: true
                    }
                },
                {
                    path: 'charts',
                    name: 'Charts',
                    component: Charts,
                    meta: {
                        requiresUser: true
                    }
                },
                {
                    path: 'widgets',
                    name: 'Widgets',
                    component: Widgets,
                    meta: {
                        requiresUser: true
                    }
                },
                {
                    path: 'menu',
                    meta: {label: 'Menu'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            component: Menus,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: 'create',
                            meta: {label: 'Create Menu'},
                            name: 'CreateMenu',
                            component: CreateMenu,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id/edit',
                            meta: {label: 'Edit Menu'},
                            name: 'EditMenu',
                            component: EditMenu,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id/delete',
                            meta: {label: 'Delete Menu'},
                            name: 'DeleteMenu',
                            component: DeleteMenu,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                    ]
                },
                {
                    path: 'menuelement',
                    meta: {label: 'MenuElement'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: ':menu/menuelement',
                            component: MenuElements,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':menu/menuelement/create',
                            meta: {label: 'Create Menu Element'},
                            name: 'Create Menu Element',
                            component: CreateMenuElement,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':menu/menuelement/:id',
                            meta: {label: 'Menu Element Details'},
                            name: 'Menu Element',
                            component: ShowMenuElement,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':menu/menuelement/:id/edit',
                            meta: {label: 'Edit Menu Element'},
                            name: 'Edit Menu Element',
                            component: EditMenuElement,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':menu/menuelement/:id/delete',
                            meta: {label: 'Delete Menu Element'},
                            name: 'Delete Menu Element',
                            component: DeleteMenuElement,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                    ]
                },
                {
                    path: 'users',
                    meta: {label: 'Users'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            component: Users,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id',
                            meta: {label: 'User Details'},
                            name: 'User',
                            component: User,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id/edit',
                            meta: {label: 'Edit User'},
                            name: 'Edit User',
                            component: EditUser,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                    ]
                },
                {
                    path: 'notes',
                    meta: {label: 'Notes'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            component: Notes,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'create',
                            meta: {label: 'Create Note'},
                            name: 'Create Note',
                            component: CreateNote,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: ':id',
                            meta: {label: 'Note Details'},
                            name: 'Note',
                            component: Note,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: ':id/edit',
                            meta: {label: 'Edit Note'},
                            name: 'Edit Note',
                            component: EditNote,
                            meta: {
                                requiresUser: true
                            }
                        },
                    ]
                },
                {
                    path: 'roles',
                    meta: {label: 'Roles'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            component: Roles,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: 'create',
                            meta: {label: 'Create Role'},
                            name: 'Create Role',
                            component: CreateRole,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id',
                            meta: {label: 'Role Details'},
                            name: 'Role',
                            component: Role,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id/edit',
                            meta: {label: 'Edit Role'},
                            name: 'Edit Role',
                            component: EditRole,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                    ]
                },
                {
                    path: 'bread',
                    meta: {label: 'Bread'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            component: Breads,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: 'create',
                            meta: {label: 'Create Bread'},
                            name: 'CreateBread',
                            component: CreateBread,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id',
                            meta: {label: 'Bread Details'},
                            name: 'Bread',
                            component: Bread,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id/edit',
                            meta: {label: 'Edit Bread'},
                            name: 'Edit Bread',
                            component: EditBread,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id/delete',
                            meta: {label: 'Delete Bread'},
                            name: 'Delete Bread',
                            component: DeleteBread,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                    ]
                },
                {
                    path: 'email',
                    meta: {label: 'Emails'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            component: Emails,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: 'create',
                            meta: {label: 'Create Email Template'},
                            name: 'Create Email Template',
                            component: CreateEmail,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id',
                            meta: {label: 'Show Email Template'},
                            name: 'Show Email Tempalte',
                            component: ShowEmail,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id/edit',
                            meta: {label: 'Edit Email Tempalate'},
                            name: 'Edit Email Template',
                            component: EditEmail,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                        {
                            path: ':id/sendEmail',
                            meta: {label: 'Send Email'},
                            name: 'Send Email',
                            component: SendEmail,
                            meta: {
                                requiresAdmin: true
                            }
                        },
                    ]
                },
                {
                    path: 'resource',
                    meta: {label: 'Resources'},
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: ':bread/resource',
                            component: Resources,
                        },
                        {
                            path: ':bread/resource/create',
                            meta: {label: 'Create Resource'},
                            name: 'CreateResource',
                            component: CreateResource
                        },
                        {
                            path: ':bread/resource/:id',
                            meta: {label: 'Resource Details'},
                            name: 'Resource',
                            component: Resource,
                        },
                        {
                            path: ':bread/resource/:id/edit',
                            meta: {label: 'Edit Resource'},
                            name: 'Edit Resource',
                            component: EditResource
                        },
                        {
                            path: ':bread/resource/:id/delete',
                            meta: {label: 'Delete Resource'},
                            name: 'Delete Resource',
                            component: DeleteResource
                        },
                    ]
                },
                {
                    path: 'base',
                    redirect: '/base/cards',
                    name: 'Base',
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: 'cards',
                            name: 'Cards',
                            component: Cards,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'forms',
                            name: 'Forms',
                            component: Forms,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'switches',
                            name: 'Switches',
                            component: Switches,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'tables',
                            name: 'Tables',
                            component: Tables,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'tabs',
                            name: 'Tabs',
                            component: Tabs,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'breadcrumb',
                            name: 'Breadcrumb',
                            component: Breadcrumbs,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'carousel',
                            name: 'Carousel',
                            component: Carousels,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'collapse',
                            name: 'Collapse',
                            component: Collapses,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'jumbotron',
                            name: 'Jumbotron',
                            component: Jumbotrons,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'list-group',
                            name: 'List Group',
                            component: ListGroups,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'navs',
                            name: 'Navs',
                            component: Navs,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'navbars',
                            name: 'Navbars',
                            component: Navbars,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'pagination',
                            name: 'Pagination',
                            component: Paginations,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'popovers',
                            name: 'Popovers',
                            component: Popovers,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'progress',
                            name: 'Progress',
                            component: ProgressBars,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'tooltips',
                            name: 'Tooltips',
                            component: Tooltips,
                            meta: {
                                requiresUser: true
                            }
                        }
                    ]
                },
                {
                    path: 'buttons',
                    redirect: '/buttons/standard-buttons',
                    name: 'Buttons',
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: 'buttons',
                            name: 'Standard Buttons',
                            component: StandardButtons,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'button-group',
                            name: 'Button Group',
                            component: ButtonGroups,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'dropdowns',
                            name: 'Dropdowns',
                            component: Dropdowns,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'brand-buttons',
                            name: 'Brand Buttons',
                            component: BrandButtons,
                            meta: {
                                requiresUser: true
                            }
                        }
                    ]
                },
                {
                    path: 'icons',
                    redirect: '/icons/coreui-icons',
                    name: 'CoreUI Icons',
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: 'coreui-icons',
                            name: 'Icons library',
                            component: CoreUIIcons,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'brands',
                            name: 'Brands',
                            component: Brands,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'flags',
                            name: 'Flags',
                            component: Flags,
                            meta: {
                                requiresUser: true
                            }
                        }
                    ]
                },
                {
                    path: 'notifications',
                    redirect: '/notifications/alerts',
                    name: 'Notifications',
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: 'alerts',
                            name: 'Alerts',
                            component: Alerts,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'badge',
                            name: 'Badge',
                            component: Badges,
                            meta: {
                                requiresUser: true
                            }
                        },
                        {
                            path: 'modals',
                            name: 'Modals',
                            component: Modals,
                            meta: {
                                requiresUser: true
                            }
                        }
                    ]
                }
            ]
        },
        {
            path: '/pages',
            redirect: '/pages/404',
            name: 'Pages',
            component: {
                render(c) {
                    return c('router-view')
                }
            },
            children: [
                {
                    path: '404',
                    name: 'Page404',
                    component: Page404
                },
                {
                    path: '500',
                    name: 'Page500',
                    component: Page500
                },
            ]
        },
        {
            path: '/',
            redirect: '/login',
            name: 'Auth',
            component: {
                render(c) {
                    return c('router-view')
                }
            },
            children: [
                {
                    path: 'login',
                    name: 'Login',
                    component: Login
                },
                {
                    path: 'register',
                    name: 'Register',
                    component: Register
                },
            ]
        },
        {
            path: '*',
            name: '404',
            component: Page404
        }
    ]
}
