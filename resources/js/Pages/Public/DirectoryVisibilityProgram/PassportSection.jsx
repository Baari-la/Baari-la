import { useState } from "react";
import { motion } from "framer-motion";
import { CheckCircle2, ArrowRight } from "lucide-react";
import { Link } from "@inertiajs/react";

import SectionTitle from "./components/SectionTitle";
import PassportCard from "./components/PassportCard";
import {
    Building2,
    Boxes,
    Factory,
    Cpu,
    ShieldCheck,
    Globe2,
    Clock3,
    Images,
    BrainCircuit,
} from "lucide-react";

export default function PassportSection({ content }) {
    const features = content?.features ?? [];
    const closing = content?.closing ?? {};
    const featureIcons = [
        Building2,
        Boxes,
        Factory,
        Cpu,
        ShieldCheck,
        Globe2,
        Clock3,
        Images,
        BrainCircuit,
    ];
    const [active, setActive] = useState(null);
    return (
        <section className="bg-gradient-to-b from-white via-slate-50 to-white py-24">
            <div className="mx-auto max-w-7xl px-6">
                {/* ======================================
                    TITLE
                ====================================== */}

                <SectionTitle
                    badge={content?.badge}
                    title={content?.title}
                    description={content?.description}
                />

                {/* ======================================
                    PASSPORT PREVIEW
                ====================================== */}

                <motion.div
                    initial={{ opacity: 0, y: 40 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.6 }}
                    className="mt-16"
                >
                    <PassportCard />
                </motion.div>

                {/* ======================================
                    INTRODUCTION
                ====================================== */}

                <motion.div
                    initial={{ opacity: 0, y: 25 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.5 }}
                    className="mx-auto mt-20 max-w-4xl text-center"
                >
                    <h3 className="text-3xl font-black text-slate-900 md:text-4xl">
                        {content?.introduction?.title}
                    </h3>

                    <p className="mx-auto mt-6 max-w-3xl text-lg leading-8 text-slate-600">
                        {content?.introduction?.description}
                    </p>
                </motion.div>

                {/* ======================================
                    PASSPORT FEATURES
                ====================================== */}

                <div className="mt-16 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {(content.features ?? []).map((feature, index) => {
                        const Icon = featureIcons[index] ?? CheckCircle2;

                        return (
                            <motion.div
                                key={`${feature.title}-${index}`}
                                initial={{
                                    opacity: 0,
                                    y: 24,
                                }}
                                whileInView={{
                                    opacity: 1,
                                    y: 0,
                                }}
                                viewport={{
                                    once: true,
                                    amount: 0.2,
                                }}
                                transition={{
                                    duration: 0.45,
                                    delay: index * 0.05,
                                }}
                                className="
                    group
                    rounded-3xl
                    border
                    border-slate-200
                    bg-white
                    p-7
                    shadow-sm
                    transition-all
                    duration-300
                    hover:-translate-y-1
                    hover:border-cyan-300
                    hover:shadow-xl
                "
                            >
                                <div
                                    className="
                        flex
                        h-14
                        w-14
                        items-center
                        justify-center
                        rounded-2xl
                        bg-cyan-50
                        text-cyan-700
                        transition
                        group-hover:bg-cyan-600
                        group-hover:text-white
                    "
                                >
                                    <Icon className="h-7 w-7" />
                                </div>

                                <h3
                                    className="
                        mt-6
                        text-xl
                        font-bold
                        text-slate-900
                    "
                                >
                                    {feature.title}
                                </h3>

                                <p
                                    className="
                        mt-3
                        leading-7
                        text-slate-600
                    "
                                >
                                    {feature.description}
                                </p>
                            </motion.div>
                        );
                    })}
                </div>

                {/* ======================================
                    CLOSING
                ====================================== */}

                <motion.div
                    initial={{
                        opacity: 0,
                        y: 35,
                    }}
                    whileInView={{
                        opacity: 1,
                        y: 0,
                    }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.6 }}
                    className="mt-24"
                >
                    <div
                        className="
                            relative
                            overflow-hidden
                            rounded-3xl
                            bg-gradient-to-r
                            from-slate-900
                            via-indigo-900
                            to-slate-900
                            px-8
                            py-14
                            text-center
                            text-white
                            shadow-2xl
                            md:px-12
                        "
                    >
                        {/* Decorative background */}

                        <div className="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-cyan-400/10 blur-3xl" />

                        <div className="pointer-events-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-indigo-400/10 blur-3xl" />

                        <div className="relative mx-auto max-w-4xl">
                            <span
                                className="
                                    inline-flex
                                    rounded-full
                                    border
                                    border-cyan-400/20
                                    bg-cyan-400/10
                                    px-4
                                    py-2
                                    text-xs
                                    font-bold
                                    uppercase
                                    tracking-[0.2em]
                                    text-cyan-300
                                "
                            >
                                Digital Company Passport™
                            </span>

                            <h3 className="mt-6 text-3xl font-black md:text-4xl">
                                {closing.title}
                            </h3>

                            <p className="mx-auto mt-6 max-w-3xl text-lg leading-8 text-slate-300">
                                {closing.description}
                            </p>

                            <div className="mt-10">
                                <Link
                                    href={route(
                                        "program.digital-directory.package",
                                    )}
                                    className="
                                        inline-flex
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-xl
                                        bg-emerald-500
                                        px-7
                                        py-4
                                        font-bold
                                        text-white
                                        shadow-lg
                                        transition-all
                                        duration-200
                                        hover:-translate-y-0.5
                                        hover:bg-emerald-600
                                        hover:shadow-xl
                                    "
                                >
                                    {closing.button}

                                    <ArrowRight className="h-5 w-5" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </motion.div>
            </div>
        </section>
    );
}
