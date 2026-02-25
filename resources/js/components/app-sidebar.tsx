import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import {
    admin,
    architect,
    home,
    opportunity,
    project,
    quote,
} from '@/routes/dashboard';
import { SharedData, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { BookOpen, Folder, Home, LayoutGrid, Shield } from 'lucide-react';
import AppLogo from './app-logo';

function getMainNavItems(isAdmin: boolean): NavItem[] {
    const mainNavItems: NavItem[] = [
        ...(isAdmin ? [{ title: 'Admin', href: admin(), icon: Shield }] : []),
        {
            title: 'Home',
            href: home(),
            icon: Home,
        },
        {
            title: 'Architects',
            href: architect(),
            icon: LayoutGrid,
            openDialog: 'createArchitect',
        },
        {
            title: 'Opportunities',
            href: opportunity(),
            icon: LayoutGrid,
        },
        {
            title: 'Projects',
            href: project(),
            icon: LayoutGrid,
        },
        {
            title: 'Quotes',
            href: quote(),
            icon: LayoutGrid,
        },
    ];

    return [...mainNavItems];
}

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    const { userProperties } = usePage<SharedData>().props.auth;
    const isAdmin = userProperties.isAdministrator;
    const mainNavItems = getMainNavItems(isAdmin);

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
