import {
    ArrowRightStartOnRectangleIcon,
    PhoneArrowDownLeftIcon,
    PhoneXMarkIcon,
    MegaphoneIcon,
    InboxStackIcon,
    HandThumbDownIcon,
    HandThumbUpIcon,
    HomeIcon,
    RocketLaunchIcon,
    CurrencyPoundIcon,
    BookOpenIcon
} from "@heroicons/vue/24/outline/index.js";
import {usePage} from "@inertiajs/vue3";
import {BarsArrowUpIcon} from "@heroicons/vue/16/solid/index.js";
import {ExclamationTriangleIcon} from "@heroicons/vue/24/solid";

export default function NavigationList() {
    const NavList = {
        main: [
            // {
            //     path: route('dashboard'),
            //     name: 'Dashboard',
            //     icon: HomeIcon,
            // },
            {
                path: route('web.active-calls.index'),
                name: 'Active Calls',
                icon: PhoneArrowDownLeftIcon,
            },
            {
                path: route('web.phone-book-entries.index'),
                name: 'Phone Book',
                icon: BookOpenIcon,
            },
            {
                path: route('web.competition.index'),
                name: 'Competitions',
                icon: CurrencyPoundIcon,
            }
        ],
        terminated: [
            {
                path: route('web.participants.index'),
                name: 'Participants',
                icon: HandThumbUpIcon,
            },
            {
                path: route('web.entries.failed.index'),
                name: 'Non Entries',
                icon: HandThumbDownIcon,
            },
            {
                path: route('web.orphan-active-calls.index'),
                name: 'Orphaned Active Calls',
                icon: PhoneXMarkIcon,
            },
        ],
        logs: [
            {
                path: route('web.api-request-logs.index'),
                name: 'API Request Logs',
                icon: InboxStackIcon,
            },
            {
                path: route('web.shout-request-logs.index'),
                name: 'Shout Audio API Logs',
                icon: MegaphoneIcon,
            },
            {
                path: route('web.max-capacity-call-logs.index'),
                name: 'Max Capacity Call Logs',
                icon: PhoneXMarkIcon,
            }
        ],
        docs: [
            {
                path: route('web.docs.call-flow'),
                name: 'Call Flow',
                icon: RocketLaunchIcon,
            }
        ],
        tools: [
            {
                path: '/horizon',
                name: 'Queues',
                icon: BarsArrowUpIcon,
                target: '_blank',
            }
        ],
        settings: [
        ],
        user_navigation: [
            {
                path: route('logout'),
                name: 'Logout',
                icon: ArrowRightStartOnRectangleIcon,
                method: 'post',
            }
        ]
    };

    const isNavActive = (item) => {
        const currentUrl = usePage().url.split('?')[0];

        return item.path.includes(currentUrl)
    };


    return {
        NavList,
        isNavActive
    }
}
