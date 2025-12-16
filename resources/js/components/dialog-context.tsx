import {
    createContext,
    ReactNode,
    useCallback,
    useContext,
    useEffect,
    useMemo,
    useState,
} from 'react';

export type DialogId =
    | 'projectDialog'
    | 'opportunityDialog'
    | 'quoteDialog'
    | 'architectDialog';

type OpenMap = Record<DialogId, boolean>;

type Ctx = {
    openMap: OpenMap;
    open: (id: DialogId) => void;
    close: (id: DialogId) => void;
    toggle: (id: DialogId) => void;
    closeAll: () => void;
};

const GlobalDialogContext = createContext<Ctx | null>(null);

const DEFAULT_STATE: OpenMap = {
    projectDialog: false,
    opportunityDialog: false,
    quoteDialog: false,
    architectDialog: false,
};

export function GlobalDialogProvider({ children }: { children: ReactNode }) {
    const [openMap, setOpenMap] = useState<OpenMap>(DEFAULT_STATE);

    const open = useCallback(
        (id: DialogId) => setOpenMap((s) => ({ ...s, [id]: true })),
        [],
    );
    const close = useCallback(
        (id: DialogId) => setOpenMap((s) => ({ ...s, [id]: false })),
        [],
    );
    const toggle = useCallback(
        (id: DialogId) => setOpenMap((s) => ({ ...s, [id]: !s[id] })),
        [],
    );
    const closeAll = useCallback(() => setOpenMap(DEFAULT_STATE), []);

    useEffect(() => {
        const isMac = navigator.platform.startsWith('Mac');

        const isTypingInInput = (el: EventTarget | null) => {
            if (!(el instanceof HTMLElement)) return false;
            const tag = el.tagName;
            const editable = (el as HTMLElement).isContentEditable;
            return (
                editable ||
                tag === 'INPUT' ||
                tag === 'TEXTAREA' ||
                tag === 'SELECT' ||
                el.getAttribute('role') === 'textbox'
            );
        };

        const handler = (e: KeyboardEvent) => {
            // Don’t steal keys while user is typing
            if (isTypingInInput(e.target)) return;

            const mod = isMac ? e.metaKey : e.ctrlKey;
            const shift = e.shiftKey;
            const k = e.key.toLowerCase();

            // Map: Cmd/Ctrl + Shift + (P/O/Q/A)
            if (mod && shift) {
                if (k === 'p') {
                    e.preventDefault();
                    open('projectDialog');
                    return;
                }
                if (k === 'o') {
                    e.preventDefault();
                    open('opportunityDialog');
                    return;
                }
                if (k === 'q') {
                    e.preventDefault();
                    open('quoteDialog');
                    return;
                }
                if (k === 'a') {
                    e.preventDefault();
                    open('architectDialog');
                    return;
                }
            }

            // Esc closes any open dialog
            if (k === 'escape') {
                // Let shadcn Dialog handle internal Esc first; if none open, closeAll does nothing
                closeAll();
            }
        };

        window.addEventListener('keydown', handler);
        return () => window.removeEventListener('keydown', handler);
    }, [open, closeAll]);

    const value = useMemo(
        () => ({ openMap, open, close, toggle, closeAll }),
        [openMap, open, close, toggle, closeAll],
    );
    return (
        <GlobalDialogContext.Provider value={value}>
            {children}
        </GlobalDialogContext.Provider>
    );
}

export function useGlobalDialogs() {
    const ctx = useContext(GlobalDialogContext);
    if (!ctx)
        throw new Error(
            'useGlobalDialogs must be used within GlobalDialogProvider',
        );
    return ctx;
}

/** Convenience hook for a single dialog id */
export function useDialog(id: DialogId) {
    const { openMap, open, close, toggle } = useGlobalDialogs();
    return {
        open: openMap[id],
        openDialog: () => open(id),
        closeDialog: () => close(id),
        toggleDialog: () => toggle(id),
    };
}
