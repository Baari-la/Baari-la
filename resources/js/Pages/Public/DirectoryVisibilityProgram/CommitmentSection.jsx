import { motion } from "framer-motion";
import { ShieldCheck, Globe2, Network, Sparkles } from "lucide-react";

import SectionTitle from "./components/SectionTitle";
import FeatureCard from "./components/FeatureCard";
import CTAButton from "./components/CTAButton";

const iconMap = {
    shield: ShieldCheck,
    globe: Globe2,
    network: Network,
    sparkles: Sparkles,
};

const colorMap = {
    shield: "bg-emerald-100 text-emerald-700",
    globe: "bg-blue-100 text-blue-700",
    network: "bg-cyan-100 text-cyan-700",
    sparkles: "bg-purple-100 text-purple-700",
};

export default function CommitmentSection({ content }) {
    return (
        <section className="bg-slate-900 py-24 text-white">
            <div className="mx-auto max-w-7xl px-6">
                <SectionTitle
                    dark
                    badge={content.badge}
                    title={content.title}
                    description={content.description}
                />

                <div className="mt-20 grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                    {content.items.map((item, index) => {
                        const Icon = iconMap[item.icon];

                        return (
                            <motion.div
                                key={`${item.icon}-${index}`}
                                initial={{
                                    opacity: 0,
                                    y: 25,
                                }}
                                whileInView={{
                                    opacity: 1,
                                    y: 0,
                                }}
                                viewport={{ once: true }}
                                transition={{
                                    delay: index * 0.08,
                                }}
                            >
                                <FeatureCard
                                    icon={Icon}
                                    title={item.title}
                                    description={item.description}
                                    color={colorMap[item.icon]}
                                />
                            </motion.div>
                        );
                    })}
                </div>

                <motion.div
                    initial={{
                        opacity: 0,
                        y: 20,
                    }}
                    whileInView={{
                        opacity: 1,
                        y: 0,
                    }}
                    viewport={{ once: true }}
                    className="
                        mx-auto
                        mt-24
                        max-w-4xl
                        border-t
                        border-white/10
                        pt-12
                        text-center
                    "
                >
                    <h3 className="text-3xl font-black">
                        {content.signature.title}
                    </h3>

                    <p className="mt-3 text-lg text-emerald-300">
                        {content.signature.subtitle}
                    </p>

                    <p className="mx-auto mt-8 max-w-3xl text-xl font-semibold italic text-white">
                        {content.signature.quote}
                    </p>

                    <div className="mt-10">
                        <CTAButton
                            href={route("program.digital-directory.package")}
                            variant="success"
                        >
                            {content.signature.button}
                        </CTAButton>
                    </div>
                </motion.div>
            </div>
        </section>
    );
}
