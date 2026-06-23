export default function WelcomeBanner({ user, memberStatus }) {
    return (
        <div className="relative overflow-hidden rounded-[32px] bg-gradient-to-r from-[#0B1F3A] via-[#102C57] to-[#1B3B6F] p-8 lg:p-10 mb-8 shadow-2xl border border-white/10">
            {/* Background Glow */}
            <div className="absolute -top-20 -right-20 w-72 h-72 bg-cyan-400/10 rounded-full blur-3xl"></div>
            <div className="absolute -bottom-20 -left-20 w-72 h-72 bg-amber-400/10 rounded-full blur-3xl"></div>

            <div className="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                {/* LEFT SIDE */}
                <div>
                    <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-500/10 border border-cyan-400/20 text-cyan-300 text-[11px] font-bold uppercase tracking-widest mb-4">
                        <span className="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        Intelligence System Online
                    </div>

                    <h1 className="text-3xl lg:text-5xl font-black tracking-tight text-white">
                        Welcome Back,
                    </h1>

                    <h2 className="text-2xl lg:text-4xl font-black text-amber-400 mt-1">
                        {user?.name || "Executive Member"}
                    </h2>

                    <p className="mt-4 text-slate-300 max-w-2xl leading-relaxed">
                        Access real-time textile market intelligence, trade
                        analytics, sourcing opportunities, logistics monitoring,
                        and industrial forecasting from a single command center.
                    </p>

                    <div className="flex flex-wrap items-center gap-3 mt-6">
                        <span className="px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-slate-300">
                            🌎 Global Trade Monitor
                        </span>

                        <span className="px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-slate-300">
                            📈 Market Intelligence
                        </span>

                        <span className="px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-slate-300">
                            🚢 Logistics Radar
                        </span>

                        <span className="px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-slate-300">
                            🤖 AI Forecast Engine
                        </span>
                    </div>
                </div>

                {/* RIGHT SIDE */}
                <div className="flex flex-col gap-4 min-w-[260px]">
                    <div className="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm">
                        <p className="text-[10px] uppercase tracking-widest text-slate-400 font-bold">
                            Membership Status
                        </p>

                        <h3 className="text-xl font-black text-amber-400 mt-1">
                            {memberStatus}
                        </h3>
                    </div>

                    <div className="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm">
                        <p className="text-[10px] uppercase tracking-widest text-slate-400 font-bold">
                            Platform Status
                        </p>

                        <h3 className="text-lg font-black text-green-400 mt-1">
                            All Systems Operational
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    );
}
