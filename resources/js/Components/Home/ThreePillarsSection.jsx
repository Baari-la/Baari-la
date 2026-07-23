import { Brain, Eye, Network } from "lucide-react";
import { usePage } from "@inertiajs/react";

export default function ThreePillarsSection() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const pillars = [
        {
            title: "INTELLIGENCE",
            icon: Brain,
            items: [
                "Executive Intelligence™",
                "Trade Intelligence™",
                "Market Intelligence™",
            ],
        },

        {
            title: "VISIBILITY",
            icon: Eye,
            items: [
                "Digital Company Passport™",
                "Visibility Score™",
                "Digital Directory™",
            ],
        },

        {
            title: "ECOSYSTEM",
            icon: Network,
            items: [
                "Smart Business Matching™",
                "Build My Supply Chain™",
                "Buyer Discovery™",
            ],
        },
    ];

    return (
        <section className="py-24">
            <div className="max-w-7xl mx-auto px-6">
                <div className="text-center">
                    <div className="text-yellow-500 text-xs font-black tracking-[0.4em]">
                        DIGESTEX FRAMEWORK
                    </div>

                    <h2 className="mt-4 text-5xl font-black">
                        {isEn ? "Three Core Pillars" : "Tiga Pilar Utama"}
                    </h2>

                    <p className="mt-4 max-w-3xl mx-auto text-gray-400">
                        {isEn
                            ? "DIGESTEX is building a Global Textile Industry Ecosystem connecting intelligence, visibility, and business opportunities."
                            : "DIGESTEX membangun Ekosistem Industri Tekstil Global yang menghubungkan intelligence, visibility, dan peluang bisnis."}
                    </p>
                </div>

                <div className="mt-16 grid gap-8 lg:grid-cols-3">
                    {pillars.map((pillar) => (
                        <div
                            key={pillar.title}
                            className="
                                rounded-3xl
                                border
                                border-white/10
                                bg-white/5
                                p-8
                                backdrop-blur-xl
                            "
                        >
                            <pillar.icon className="h-10 w-10 text-yellow-500" />

                            <h3 className="mt-6 text-2xl font-black">
                                {pillar.title}
                            </h3>

                            <div className="mt-6 space-y-3">
                                {pillar.items.map((item) => (
                                    <div key={item} className="text-gray-300">
                                        • {item}
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
