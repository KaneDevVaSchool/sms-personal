export type NavItem = {
    label: string;
    routeName: string;
    icon?: string;
};

export const mainNav: NavItem[] = [
    { label: 'Dashboard', routeName: 'dashboard' },
    { label: 'Team', routeName: 'team.users.index' },
    { label: 'Resources', routeName: 'resources.index' },
    { label: 'Websites', routeName: 'websites.index' },
    { label: 'Projects', routeName: 'projects.index' },
    { label: 'Knowledge', routeName: 'knowledge.posts.index' },
    { label: 'Contracts', routeName: 'contracts.index' },
    { label: 'AI Accounts', routeName: 'ai-accounts.index' },
    { label: 'OKRs', routeName: 'objectives.index' },
];
