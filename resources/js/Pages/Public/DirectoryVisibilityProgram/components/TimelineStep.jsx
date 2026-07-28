import { motion } from "framer-motion";

export default function TimelineStep({ step }) {
    if (!step) {
        return null;
    }

    return (
        <motion.div
            whileHover={{
                y: -6,
            }}
            transition={{
                duration: 0.2,
            }}
            className="
                group
                relative
                h-full
                overflow-hidden
                rounded-3xl
                border
                border-slate-200
                bg-white
                p-8
                shadow-sm
                transition-all
                duration-300
                hover:border-cyan-300
                hover:shadow-xl
            "
        >
            {/* NUMBER */}

            <div
                className="
                    flex
                    h-14
                    w-14
                    items-center
                    justify-center
                    rounded-2xl
                    bg-cyan-100
                    text-lg
                    font-black
                    text-cyan-700
                    transition-colors
                    duration-300
                    group-hover:bg-cyan-600
                    group-hover:text-white
                "
            >
                {step.number}
            </div>

            {/* TITLE */}

            <h3
                className="
                    mt-7
                    text-2xl
                    font-bold
                    leading-tight
                    text-slate-900
                "
            >
                {step.title}
            </h3>

            {/* DESCRIPTION */}

            <p
                className="
                    mt-5
                    leading-8
                    text-slate-600
                "
            >
                {step.description}
            </p>

            {/* DECORATION */}

            <div
                className="
                    absolute
                    -bottom-16
                    -right-16
                    h-32
                    w-32
                    rounded-full
                    bg-cyan-100/50
                    transition-all
                    duration-300
                    group-hover:scale-125
                    group-hover:bg-cyan-200/50
                "
            />
        </motion.div>
    );
}
