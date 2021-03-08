export default [
  {
    _name: 'CSidebarNav',
    _children: [
      {
        _name: 'CSidebarNavItem',
        name: 'Today',
        to: '/',
        icon: 'cil-speedometer',
      },
      {
        _name: 'CSidebarNavItem',
        name: 'Tasks',
        to: '/tasks',
        icon: 'cil-task',
        badge: {
          color: 'primary',
          text: 'NEW'
        }
      },
      {
        _name: 'CSidebarNavItem',
        name: 'Jobs',
        to: '/jobs',
        icon: 'cil-library'
      },
      {
        _name: 'CSidebarNavItem',
        name: 'Clients',
        to: '/clients',
        icon: 'cil-building'
      }
    ]
  }
]

