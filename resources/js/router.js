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

//Timers
import Timers from "./views/Timers"


// Authentication
import Login from './views/AuthLogin';

import Page404 from './components/examples/pages/Page404';
import Register from './components/examples/pages/Register';


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
                },
                {
                    path: 'timers',
                    component: {
                        render(c) {
                            return c('router-view')
                        }
                    },
                    children: [
                        {
                            path: '',
                            name: 'Timers',
                            meta: {
                                requiresUser: true,
                                pageTitle: "Timers",
                                links: [
                                    {name: 'Active', href: '/timers', active: true},
                                    {name: 'Paused', href: '/timers/paused'}
                                ]
                            },
                            component: Timers,
                        },
                        {
                            path: 'paused',
                            name: 'TimersPaused',
                            component: Timers,
                            meta: {
                                requiresUser: true,
                                pageTitle: "Clients",
                                links: [
                                    {name: 'Active', href: '/timers'},
                                    {name: 'Paused', href: '/timers/paused', active: true}
                                ]
                            }
                        },
                        {
                            path: ':userId',
                            name: 'Client',
                            component: Timers,
                            meta: {
                                requiresUser: true,
                                pageTitle: "View Client",
                                links: [
                                    {name: 'Active', href: '/timers'},
                                    {name: 'Paused', href: '/timers/paused'}
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
