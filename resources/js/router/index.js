import { createWebHistory, createRouter } from 'vue-router';
import store from '@/store';    

const Home = () => import('@/components/Home.vue');
const Login = () => import('@/components/login.vue');

const routes = [
    {
        name: "login",
        path: "/",
        component: Home,
        meta: {
            middleware: "guest",
            title: "Login"
        },
        children: [
            {
                name: "Home",
                path: '/home',
                component: Home,
                meta: {
                    title: "Home"
                }
            }
        ]
    }
    // {
    //     name: "Home",
    //     path: "/",
    //     component: Home,
    //     meta: {
    //         title: "Home"
    //     },
    // }
];
const router = createRouter({
    history: createWebHistory(),
    routes, 
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "Home" })
        }
        next()
    } else {
        if (store.state.auth.authenticated) {
            next()
        } else {
            next({ name: "login" })
        }
    }
})

export default router;