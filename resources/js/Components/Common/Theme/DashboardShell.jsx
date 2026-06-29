export default function DashboardShell({ children }) {
    return (
        <main
            className="

                min-h-screen

                bg-slate-50

            "
        >
            <div
                className="

                    mx-auto

                    max-w-7xl

                    space-y-10

                    px-6

                    py-10

                "
            >
                {children}
            </div>
        </main>
    );
}
