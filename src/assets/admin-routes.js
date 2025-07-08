import AdminLayout from './Admin/AdminLayout'
import Dashboard from './Admin/Dashboard'
import * as helorui from 'helorui'

let routes =
[
    {
        path: '/',
        component: AdminLayout,
        redirect: '/admin-dashboard',
        children: [
            {
                path: '/admin-dashboard',
                name: 'admin-dashboard',
                component: Dashboard,
            },
        ],
    },
];

const resources = window.cms.resources;

resources.forEach(function(resource)
{
    const {
        name,
        title,
        singleton,
        table,
    } = resource;

    let route = {
        path: '/' + name,
        name,
        component: (singleton === true) ? helorui.components.GenericSingleton : helorui.components.GenericTable,
        props: {
            title,
            resource: name,
            ...table,
        },
    }

    routes[0].children.push(route);
});

export default routes;
