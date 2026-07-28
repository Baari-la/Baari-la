import React from "react";
import { motion } from "framer-motion";
import { Sparkles } from "lucide-react";

import CTAButton from "./components/CTAButton";
import JourneyCard from "./components/JourneyCard";

export default function HeroSection({ content }) {
    return (
        <section className="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-blue-900">
            {/* ===========================================
                BACKGROUND
            =========================================== */}

            <div className="absolute inset-0">
                <div className="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-cyan-500/10 blur-3xl" />
                <div className="absolute bottom-0 left-0 h-80 w-80 rounded-full bg-blue-500/10 blur-3xl" />
            </div>

            <div className="relative mx-auto max-w-7xl px-6 py-24 lg:px-8">
                <div className="grid items-center gap-16 lg:grid-cols-2">
                    {/* ======================================
                        LEFT
                    ====================================== */}

                    <motion.div
                        initial={{
                            opacity: 0,
                            x: -40,
                        }}
                        animate={{
                            opacity: 1,
                            x: 0,
                        }}
                        transition={{
                            duration: 0.7,
                        }}
                    >
                        <div
                            className="
                                inline-flex
                                items-center
                                rounded-full
                                border
                                border-cyan-400/30
                                bg-cyan-400/10
                                px-4
                                py-2
                                text-sm
                                font-medium
                                text-cyan-200
                            "
                        >
                            <Sparkles className="mr-2 h-4 w-4" />

                            {content.badge}
                        </div>

                        <p
                            className="
                                mt-8
                                whitespace-pre-line
                                text-3xl
                                font-light
                                leading-tight
                                tracking-tight
                                text-white
                                md:text-4xl
                            "
                        >
                            {content.headline}
                        </p>

                        <h1
                            className="
                                mt-8
                                whitespace-pre-line
                                text-5xl
                                font-black
                                leading-tight
                                text-white
                                md:text-6xl
                            "
                        >
                            {content.title}
                        </h1>

                        <p
                            className="
                                mt-4
                                text-sm
                                uppercase
                                tracking-[0.25em]
                                text-cyan-300
                            "
                        >
                            {content.ecosystem}
                        </p>

                        <p
                            className="
                                mt-6
                                text-lg
                                font-semibold
                                text-emerald-300
                            "
                        >
                            {content.tagline}
                        </p>

                        <p
                            className="
                                mt-6
                                max-w-2xl
                                text-lg
                                leading-8
                                text-slate-300
                            "
                        >
                            {content.description}
                        </p>

                        {/* ==================================
                            BUTTONS
                        ================================== */}

                        <div className="mt-10 flex flex-wrap gap-5">
                            <CTAButton
                                href={route(
                                    "program.digital-directory.package",
                                )}
                                variant="primary"
                            >
                                {content.joinButton}
                            </CTAButton>

                            <CTAButton
                                href={route("program.digital-directory")}
                                variant="outline-dark"
                            >
                                {content.learnButton}
                            </CTAButton>
                        </div>
                    </motion.div>

                    {/* ======================================
                        RIGHT
                    ====================================== */}

                    <motion.div
                        initial={{
                            opacity: 0,
                            x: 40,
                        }}
                        animate={{
                            opacity: 1,
                            x: 0,
                        }}
                        transition={{
                            duration: 0.7,
                        }}
                    >
                        <JourneyCard
                            title={content.journeyTitle}
                            steps={content.journey}
                        />
                    </motion.div>
                </div>
            </div>
        </section>
    );
}
