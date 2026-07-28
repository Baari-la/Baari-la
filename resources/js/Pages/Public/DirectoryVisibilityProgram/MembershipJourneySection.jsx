import { motion } from "framer-motion";

import SectionTitle from "./components/SectionTitle";
import JourneyCard from "./components/JourneyCard";
import CTAButton from "./components/CTAButton";

export default function MembershipJourneySection({ content }) {
    const levels = content?.levels ?? [];
    const closing = content?.closing ?? {};

    return (
        <section className="bg-white py-24">
            <div className="mx-auto max-w-7xl px-6">
                {/* ===========================================
                    SECTION TITLE
                =========================================== */}

                <SectionTitle
                    badge={content?.badge}
                    title={content?.title}
                    description={content?.description}
                />

                {/* ===========================================
                    MEMBERSHIP JOURNEY
                =========================================== */}

                <div
                    className="
                        mt-20
                        grid
                        gap-6
                        md:grid-cols-2
                        lg:grid-cols-3
                        xl:grid-cols-4
                    "
                >
                    {levels.map((level, index) => {
                        const levelKey =
                            level.id ??
                            level.slug ??
                            level.level ??
                            level.name ??
                            `membership-level-${index}`;

                        return (
                            <motion.div
                                key={levelKey}
                                initial={{
                                    opacity: 0,
                                    y: 30,
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
                                    duration: 0.5,
                                    delay: index * 0.08,
                                    ease: "easeOut",
                                }}
                                whileHover={{
                                    y: -6,
                                }}
                                className="h-full"
                            >
                                <JourneyCard level={level} index={index} />
                            </motion.div>
                        );
                    })}
                </div>

                {/* ===========================================
                    CLOSING CTA
                =========================================== */}

                <motion.div
                    initial={{
                        opacity: 0,
                        y: 20,
                    }}
                    whileInView={{
                        opacity: 1,
                        y: 0,
                    }}
                    viewport={{
                        once: true,
                        amount: 0.3,
                    }}
                    transition={{
                        duration: 0.5,
                    }}
                    className="
                        mx-auto
                        mt-24
                        max-w-4xl
                        text-center
                    "
                >
                    <h3
                        className="
                            text-3xl
                            font-black
                            tracking-tight
                            text-slate-900
                            md:text-4xl
                        "
                    >
                        {closing.title}
                    </h3>

                    <p
                        className="
                            mx-auto
                            mt-6
                            max-w-3xl
                            text-lg
                            leading-8
                            text-slate-600
                        "
                    >
                        {closing.description}
                    </p>

                    <div className="mt-10">
                        <CTAButton
                            href={route("program.digital-directory.package")}
                            variant="success"
                        >
                            {closing.button}
                        </CTAButton>
                    </div>
                </motion.div>
            </div>
        </section>
    );
}
