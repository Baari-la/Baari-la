import {
    Activity,
    BarChart3,
    Globe2,
    Network,
    Radar,
    ShieldCheck,
} from "lucide-react";

export default function WelcomeBanner({ user, memberStatus }) {
    return (
        <section
            className="
                relative
                mb-8
                overflow-hidden
                rounded-[32px]
                border
                border-white/10
                bg-gradient-to-r
                from-[#0B1F3A]
                via-[#102C57]
                to-[#1B3B6F]
                p-8
                shadow-2xl
                lg:p-10
            "
        >
            {/* Background */}

            <div
                className="
                    absolute
                    -right-20
                    -top-20
                    h-72
                    w-72
                    rounded-full
                    bg-cyan-400/10
                    blur-3xl
                "
            />

            <div
                className="
                    absolute
                    -bottom-20
                    -left-20
                    h-72
                    w-72
                    rounded-full
                    bg-amber-400/10
                    blur-3xl
                "
            />

            <div
                className="
                    relative
                    z-10
                    flex
                    flex-col
                    gap-10
                    xl:flex-row
                    xl:items-center
                    xl:justify-between
                "
            >
                {/* Main */}

                <div className="max-w-4xl">
                    <div
                        className="
                            inline-flex
                            items-center
                            gap-2
                            rounded-full
                            border
                            border-cyan-400/20
                            bg-cyan-500/10
                            px-4
                            py-2
                            text-[11px]
                            font-black
                            uppercase
                            tracking-[0.18em]
                            text-cyan-300
                        "
                    >
                        <Activity className="h-4 w-4" />
                        DIGESTEX Intelligence System
                    </div>

                    <p
                        className="
                            mt-7
                            text-xs
                            font-black
                            uppercase
                            tracking-[0.22em]
                            text-slate-400
                        "
                    >
                        Executive Intelligence Dashboard
                    </p>

                    <h1
                        className="
                            mt-3
                            text-3xl
                            font-black
                            tracking-tight
                            text-white
                            lg:text-5xl
                        "
                    >
                        Welcome back,{" "}
                        <span className="text-amber-400">
                            {user?.name || "Member"}
                        </span>
                    </h1>

                    <p
                        className="
                            mt-5
                            max-w-3xl
                            text-base
                            leading-7
                            text-slate-300
                        "
                    >
                        Monitor textile markets, trade flows, industrial
                        signals, supply chains, and business opportunities
                        across the DIGESTEX intelligence ecosystem.
                    </p>

                    {/* Intelligence Areas */}

                    <div
                        className="
                            mt-7
                            grid
                            gap-3
                            sm:grid-cols-2
                            xl:grid-cols-4
                        "
                    >
                        <IntelligenceItem icon={Globe2} title="Global Trade" />

                        <IntelligenceItem
                            icon={BarChart3}
                            title="Market Intelligence"
                        />

                        <IntelligenceItem
                            icon={Radar}
                            title="Industrial Radar"
                        />

                        <IntelligenceItem icon={Network} title="Supply Chain" />
                    </div>
                </div>

                {/* Status */}

                <div
                    className="
                        grid
                        min-w-0
                        gap-4
                        sm:grid-cols-2
                        xl:w-[310px]
                        xl:grid-cols-1
                    "
                >
                    <StatusCard
                        icon={ShieldCheck}
                        label="Account Status"
                        value={memberStatus || "Member"}
                        valueClassName="text-amber-400"
                    />

                    <StatusCard
                        icon={Activity}
                        label="Intelligence Platform"
                        value="Operational"
                        valueClassName="text-emerald-400"
                    />
                </div>
            </div>
        </section>
    );
}

function IntelligenceItem({ icon: Icon, title }) {
    return (
        <div
            className="
                flex
                items-center
                gap-3
                rounded-2xl
                border
                border-white/10
                bg-white/5
                px-4
                py-3
                backdrop-blur-sm
            "
        >
            <div
                className="
                    flex
                    h-9
                    w-9
                    shrink-0
                    items-center
                    justify-center
                    rounded-xl
                    bg-white/10
                    text-cyan-300
                "
            >
                <Icon className="h-4 w-4" />
            </div>

            <span
                className="
                    text-xs
                    font-bold
                    text-slate-200
                "
            >
                {title}
            </span>
        </div>
    );
}

function StatusCard({
    icon: Icon,
    label,
    value,
    valueClassName = "text-white",
}) {
    return (
        <div
            className="
                rounded-2xl
                border
                border-white/10
                bg-white/5
                p-5
                backdrop-blur-sm
            "
        >
            <div className="flex items-center gap-2 text-slate-400">
                <Icon className="h-4 w-4" />

                <p
                    className="
                        text-[10px]
                        font-black
                        uppercase
                        tracking-[0.18em]
                    "
                >
                    {label}
                </p>
            </div>

            <div
                className={`
                    mt-3
                    text-lg
                    font-black
                    ${valueClassName}
                `}
            >
                {value}
            </div>
        </div>
    );
}
