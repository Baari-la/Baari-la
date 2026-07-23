import { Link } from "@inertiajs/react";
import { Inbox } from "lucide-react";

export default function AdminEmptyState({
    title = "No Data Available",
    description = "There are currently no records to display.",
    actionText = null,
    actionHref = null,
    icon = <Inbox className="h-14 w-14" />,
}) {
    return (
        <div
            className="
                rounded-3xl
                border
                bg-white
                px-8
                py-16
                text-center
                shadow-sm
            "
        >
            {/* Icon */}

            <div
                className="
                    mx-auto
                    flex
                    h-24
                    w-24
                    items-center
                    justify-center
                    rounded-full
                    bg-slate-100
                    text-slate-500
                "
            >
                {icon}
            </div>

            {/* Title */}

            <h2
                className="
                    mt-6
                    text-3xl
                    font-black
                    text-slate-900
                "
            >
                {title}
            </h2>

            {/* Description */}

            <p
                className="
                    mx-auto
                    mt-4
                    max-w-xl
                    text-slate-500
                "
            >
                {description}
            </p>

            {/* CTA */}

            {actionText && actionHref && (
                <Link
                    href={actionHref}
                    className="
                        mt-8
                        inline-flex
                        items-center
                        rounded-2xl
                        bg-slate-900
                        px-6
                        py-4
                        font-bold
                        text-white
                        transition
                        hover:bg-slate-800
                    "
                >
                    {actionText}
                </Link>
            )}
        </div>
    );
}
