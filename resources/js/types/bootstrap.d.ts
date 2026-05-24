export {};

declare global {
    interface Window {
        bootstrap?: {
            Modal: new (
                element: Element,
                options?: Record<string, unknown>,
            ) => {
                show: () => void;
                hide: () => void;
                dispose: () => void;
            };
        };
    }
}
