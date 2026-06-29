export default function HeroBackground({ children }) {
    return (
        <div className="relative overflow-hidden">
            {/* Background Gradient */}

            <div className="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950" />

            {/* Radial Glow */}

            <div className="absolute left-0 top-0 h-[600px] w-[600px] rounded-full bg-cyan-500/10 blur-3xl" />

            <div className="absolute right-0 bottom-0 h-[500px] w-[500px] rounded-full bg-blue-600/10 blur-3xl" />

            <div className="absolute left-1/2 top-1/2 h-[450px] w-[450px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-500/10 blur-3xl" />

            {/* Grid Pattern */}

            <div
                className="absolute inset-0 opacity-[0.05]"
                style={{
                    backgroundImage: `
                        linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px)
                    `,
                    backgroundSize: "60px 60px",
                }}
            />

            {/* Textile Network */}

            <svg
                className="absolute inset-0 h-full w-full opacity-10"
                xmlns="http://www.w3.org/2000/svg"
            >
                <defs>
                    <pattern
                        id="network"
                        width="120"
                        height="120"
                        patternUnits="userSpaceOnUse"
                    >
                        <circle cx="10" cy="10" r="2" fill="#38bdf8" />

                        <circle cx="100" cy="40" r="2" fill="#38bdf8" />

                        <circle cx="60" cy="100" r="2" fill="#38bdf8" />

                        <line
                            x1="10"
                            y1="10"
                            x2="100"
                            y2="40"
                            stroke="#38bdf8"
                            strokeWidth="1"
                        />

                        <line
                            x1="100"
                            y1="40"
                            x2="60"
                            y2="100"
                            stroke="#38bdf8"
                            strokeWidth="1"
                        />

                        <line
                            x1="60"
                            y1="100"
                            x2="10"
                            y2="10"
                            stroke="#38bdf8"
                            strokeWidth="1"
                        />
                    </pattern>
                </defs>

                <rect width="100%" height="100%" fill="url(#network)" />
            </svg>

            {/* Bottom Fade */}

            <div className="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-slate-950 to-transparent" />

            {/* Content */}

            <div className="relative z-10">{children}</div>
        </div>
    );
}
