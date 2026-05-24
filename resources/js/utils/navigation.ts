export type NavChild = {
    label: string;
    routeName: string;
};

export type NavItem = {
    label: string;
    routeName: string;
    icon: string;
    children?: NavChild[];
    collapseId?: string;
};

export const mainNav: NavItem[] = [
    { label: 'Dashboard', routeName: 'dashboard', icon: 'ri-home-4-line' },
    {
        label: 'Team',
        routeName: 'team.users.index',
        icon: 'ri-team-line',
        collapseId: 'sidebarTeam',
        children: [
            { label: 'Users', routeName: 'team.users.index' },
            { label: 'Departments', routeName: 'team.departments.index' },
        ],
    },
    { label: 'Resources', routeName: 'resources.index', icon: 'ri-server-line' },
    { label: 'Websites', routeName: 'websites.index', icon: 'ri-global-line' },
    { label: 'Projects', routeName: 'projects.index', icon: 'ri-kanban-view' },
    {
        label: 'Knowledge',
        routeName: 'knowledge.posts.index',
        icon: 'ri-book-2-line',
        collapseId: 'sidebarKnowledge',
        children: [
            { label: 'Posts', routeName: 'knowledge.posts.index' },
            { label: 'Categories', routeName: 'knowledge.categories.index' },
        ],
    },
    { label: 'Contracts', routeName: 'contracts.index', icon: 'ri-file-text-line' },
    { label: 'AI Accounts', routeName: 'ai-accounts.index', icon: 'ri-robot-line' },
    { label: 'OKRs', routeName: 'objectives.index', icon: 'ri-bar-chart-box-line' },
];

export function isNavActive(routeName: string): boolean {
    try {
        return route().current(routeName) || route().current(`${routeName}.*`);
    } catch {
        return false;
    }
}

export function isNavGroupActive(item: NavItem): boolean {
    if (isNavActive(item.routeName)) {
        return true;
    }
    return item.children?.some((c) => isNavActive(c.routeName)) ?? false;
}
