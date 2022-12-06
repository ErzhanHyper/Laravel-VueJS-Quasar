import {createWebHistory, createRouter} from "vue-router";
import Home from './views/home/Home.vue'
import KnowledgeList from "./views/knowledge/KnowledgeList.vue";
import Structure from "./views/structure/Index.vue";
import LoginPage from "./views/auth/LoginPage.vue";
import MainTemplate from './layouts/MainTemplate.vue';
import Profile from "./views/profile/ProfileMain.vue";
import TrustList from "./views/trust/TrustList.vue";
import ContractList from "./views/contract/ContractList.vue";
import TemplateList from "./views/sample/SampleList.vue";
import LearningList from "./views/learning/LearningList.vue";
import LearningDetail from "./views/learning/LearningDetail.vue";
import EmployeeList from "./views/employee/EmployeeList.vue";
import NewsList from "./views/news/NewsList.vue";
import ApplicationList from "./views/application/ApplicationList.vue";
import QuoteList from "./views/quote/QuoteList.vue";
import PollingList from "./views/polling/PollingList.vue";
import FeedList from "./views/feed/FeedList.vue";
import RoleList from "./views/role/RoleList.vue";
import GalleryList from "./views/gallery/GalleryList.vue";
import GalleryDetail from "./views/gallery/GalleryDetail.vue";

const routes = [

    {
        path: '/login',
        component: LoginPage,
        name: 'login',
    },

    {
        path: '/',
        component: MainTemplate,
        name: 'main',
        redirect: '/login',
        meta: {
            requiresLogin: true,
        },
        children: [
            {
                path: '/home',
                name: 'home',
                component: Home,
                meta: {
                    requiresLogin: true,
                },
            },
            {
                path: '/knowledge',
                name: 'docs_regulation',
                component: KnowledgeList,
                meta: {
                    requiresLogin: true,
                },
            },
            {
                path: '/docs/trust',
                name: 'docs_trust',
                component: TrustList,
                meta: {
                    requiresLogin: true,
                },
            },

            {
                path: '/docs/contract',
                name: 'docs_contract',
                component: ContractList,
                meta: {
                    requiresLogin: true,
                },
            },

            {
                path: '/docs/template',
                name: 'docs_template',
                component: TemplateList,
                meta: {
                    requiresLogin: true,
                },
            },

            {
                path: '/docs/learning',
                name: 'docs_learning',
                component: LearningList,
                meta: {
                    requiresLogin: true,
                },

            },
            {
                path: '/docs/learning/:id/show',
                component: LearningDetail,
                name: 'docs_learning_show',
                meta: {
                    requiresLogin: true,
                },
                props: true
            },

            {
                path: '/employees',
                name: 'employee',
                component: EmployeeList,
                meta: {
                    requiresLogin: true,
                },
            },

            {
                path: '/news',
                name: 'news',
                component: NewsList,
                meta: {
                    requiresLogin: true,
                },
            },

            {
                path: '/application',
                name: 'application',
                component: ApplicationList,
                meta: {
                    requiresLogin: true,
                },
            },

            {
                path: '/quote',
                name: 'quote',
                component: QuoteList,
                meta: {
                    requiresLogin: true,
                },
            },

            {
                path: '/polling',
                name: 'polling',
                component: PollingList,
                meta: {
                    requiresLogin: true,
                },
            },

            {
                path: '/feed',
                name: 'feed',
                component: FeedList,
                meta: {
                    requiresLogin: true,
                },
            },

            {
                path: '/gallery',
                name: 'gallery',
                component: GalleryList,
                meta: {
                    requiresLogin: true,
                },
            },

            {
                path: '/gallery/:id/show',
                name: 'gallery_detail',
                component: GalleryDetail,
                meta: {
                    requiresLogin: true,
                },
                props: true
            },

            {
                path: '/role',
                name: 'role',
                component: RoleList,
                meta: {
                    requiresLogin: true,
                },
            },

            {
                path: '/structure',
                name: 'structure',
                component: Structure,
                meta: {
                    requiresLogin: true,
                },
            },
            {
                path: '/profile',
                component: Profile,
                name: 'profile',
                meta: {
                    requiresLogin: true,
                    type: 'read',
                    section: 'profile'
                },
            },
        ]
    }

]

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router
