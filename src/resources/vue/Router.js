
const Index = () => import('./components/l-limitless-bs4/Index');
const Form = () => import('./components/l-limitless-bs4/Form');
const Show = () => import('./components/l-limitless-bs4/Show');
const SideBarLeft = () => import('./components/l-limitless-bs4/SideBarLeft');
const SideBarRight = () => import('./components/l-limitless-bs4/SideBarRight');

const routes = [

    {
        path: '/goods-delivered',
        components: {
            default: Index,
            //'sidebar-left': ComponentSidebarLeft,
            //'sidebar-right': ComponentSidebarRight
        },
        meta: {
            title: 'Accounting :: Sales :: Delivery Notes',
            metaTags: [
                {
                    name: 'description',
                    content: 'Delivery Notes'
                },
                {
                    property: 'og:description',
                    content: 'Delivery Notes'
                }
            ]
        }
    },
    {
        path: '/goods-delivered/create',
        components: {
            default: Form,
            //'sidebar-left': ComponentSidebarLeft,
            //'sidebar-right': ComponentSidebarRight
        },
        meta: {
            title: 'Accounting :: Sales :: Delivery Note :: Create',
            metaTags: [
                {
                    name: 'description',
                    content: 'Create Delivery Note'
                },
                {
                    property: 'og:description',
                    content: 'Create Delivery Note'
                }
            ]
        }
    },
    {
        path: '/goods-delivered/:id',
        components: {
            default: Show,
            'sidebar-left': SideBarLeft,
            'sidebar-right': SideBarRight
        },
        meta: {
            title: 'Accounting :: Sales :: Delivery Note',
            metaTags: [
                {
                    name: 'description',
                    content: 'Delivery Note'
                },
                {
                    property: 'og:description',
                    content: 'Delivery Note'
                }
            ]
        }
    },
    {
        path: '/goods-delivered/:id/copy',
        components: {
            default: Form,
        },
        meta: {
            title: 'Accounting :: Sales :: Delivery Note :: Copy',
            metaTags: [
                {
                    name: 'description',
                    content: 'Copy Delivery Note'
                },
                {
                    property: 'og:description',
                    content: 'Copy Delivery Note'
                }
            ]
        }
    },
    {
        path: '/goods-delivered/:id/edit',
        components: {
            default: Form,
        },
        meta: {
            title: 'Accounting :: Sales :: Delivery Note :: Edit',
            metaTags: [
                {
                    name: 'description',
                    content: 'Edit Delivery Note'
                },
                {
                    property: 'og:description',
                    content: 'Edit Delivery Note'
                }
            ]
        }
    }

]

export default routes
