import { motion } from "framer-motion";
import { Sparkles } from "lucide-react";

import CTAButton from "./components/CTAButton";

export default function CTASection({ content }) {
    return (
        <section className="py-24">
            <div className="mx-auto max-w-7xl px-6">
                <motion.div
                    initial={{ opacity: 0, y: 25 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    className="
                        overflow-hidden
                        rounded-3xl
                        bg-gradient-to-r
                        from-slate-900
                        via-indigo-900
                        to-slate-900
                        p-12
                        text-white
                        shadow-2xl
                    "
                >
                    {/* =======================================
                        HEADER
                    ======================================= */}

                    <div className="mx-auto max-w-4xl text-center">
                        <span
                            className="
                                inline-flex
                                items-center
                                gap-2
                                rounded-full
                                bg-emerald-500/20
                                px-5
                                py-2
                                text-sm
                                font-semibold
                                uppercase
                                tracking-[0.25em]
                                text-emerald-300
                            "
                        >
                            <Sparkles className="h-4 w-4" />
                            DIGESTEX
                        </span>

                        <h2
                            className="
                                mt-6
                                text-4xl
                                font-black
                                leading-tight
                                md:text-5xl
                            "
                        >
                            {content.title}
                        </h2>

                        <p
                            className="
                                mx-auto
                                mt-6
                                max-w-3xl
                                text-lg
                                leading-8
                                text-slate-300
                            "
                        >
                            {content.description}
                        </p>
                    </div>

                    {/* =======================================
                        BUTTON
                    ======================================= */}

                    <div className="mt-14 flex justify-center">
                        <CTAButton
                            href={route("program.digital-directory.package")}
                            variant="success"
                        >
                            {content.primaryButton}
                        </CTAButton>
                    </div>

                    {/* =======================================
                        CLOSING
                    ======================================= */}

                    <div
                        className="
                            mx-auto
                            mt-16
                            max-w-4xl
                            border-t
                            border-white/10
                            pt-10
                            text-center
                        "
                    >
                        <p
                            className="
                                text-xl
                                font-semibold
                                italic
                                text-white
                            "
                        >
                            {content.closing}
                        </p>

                        <p
                            className="
                                mx-auto
                                mt-6
                                max-w-3xl
                                text-base
                                leading-8
                                text-slate-400
                            "
                        >
                            {content.note}
                        </p>

                        <div className="mt-10">
                            <h3 className="text-2xl font-black">DIGESTEX</h3>

                            <p className="mt-2 text-emerald-300">
                                Global Textile Intelligence Ecosystem
                            </p>
                        </div>
                    </div>
                </motion.div>
            </div>
        </section>
    );
}
