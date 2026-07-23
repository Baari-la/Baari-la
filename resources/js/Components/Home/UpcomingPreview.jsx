import { Cpu, Radar, Package } from "lucide-react";
import { Link, usePage } from "@inertiajs/react";

export default function UpcomingPreview() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const items = [
        {
            title: "Cotton Intelligence™",
            icon: Package,
        },

        {
            title: "Global Trade Radar™",
            icon: Radar,
        },

        {
            title: "Executive AI Insight™",
            icon: Cpu,
        },
    ];

    return (
        <section className="bg-slate-50 py-10">
            <div className="mx-auto max-w-7xl px-6">
                {/* Header */}

                <div className="mx-auto max-w-4xl text-center">
                    <span
                        className="
                            inline-flex
                            rounded-full
                            bg-amber-100
                            px-4
                            py-2
                            text-sm
                            font-black
                            text-amber-700
                        "
                    >
                        {isEn ? "UPCOMING" : "AKAN HADIR"}
                    </span>

                    <h2 className="mt-2 text-4xl font-black">
                        {isEn
                            ? "THE FUTURE OF DIGESTEX"
                            : "MASA DEPAN DIGESTEX"}
                    </h2>

                    <p className="mt-2 text-base text-slate-600">
                        {isEn
                            ? "Discover the next generation of textile intelligence."
                            : "Temukan generasi berikutnya dari textile intelligence."}
                    </p>
                </div>

                {/* Cards */}

                {/* Cards */}

                <div className="mt-10 mx-auto flex max-w-4xl flex-wrap justify-center gap-4">
                    <div
                        className="
            flex
            max-w-5xl
            flex-wrap
            items-center
            justify-center
            gap-4
        "
                    >
                        {items.map((item) => {
                            const Icon = item.icon;

                            return (
                                <div
                                    key={item.title}
                                    className="
                        flex
                        items-center
                        gap-3
                        rounded-2xl
                        border
                        border-slate-200
                        bg-white
                        px-5
                        py-4
                        shadow-sm
                        transition
                        hover:-translate-y-1
                        hover:shadow-md
                    "
                                >
                                    <Icon className="h-5 w-5 text-indigo-600" />

                                    <div>
                                        <div className="font-bold text-slate-900">
                                            {item.title}
                                        </div>

                                        <div
                                            className="
                                text-[10px]
                                font-black
                                uppercase
                                tracking-wider
                                text-amber-600
                            "
                                        >
                                            {isEn ? "UPCOMING" : "AKAN HADIR"}
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>

                {/* CTA */}

                <div className="mt-16 text-center">
                    <Link
                        href={route("future-of-digestex")}
                        className="
                            inline-flex
                            items-center
                            rounded-2xl
                            bg-slate-900
                            px-8
                            py-4
                            font-bold
                            text-white
                            transition
                            hover:bg-slate-800
                        "
                    >
                        {isEn ? "VIEW ROADMAP" : "LIHAT ROADMAP"}
                    </Link>
                </div>
                <div className="mt-16 text-center">
                    <Link
                        href={route("cotton-intelligence")}
                        className="
            rounded-2xl
            bg-emerald-500
            px-8
            py-4
            text-white
            font-bold
        "
                    >
                        EXPLORE COTTON INTELLIGENCE
                    </Link>
                </div>
            </div>
        </section>
    );
}
