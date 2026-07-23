import { TrendingUp } from "lucide-react";

export default function AdminStatsCard({
    title,
    value,
    icon,
    subtitle = null,
    trend = null,
}) {
    return (
        <div
            className="
                overflow-hidden
                rounded-3xl
                border
                bg-white
                p-6
                shadow-sm
                transition
                hover:shadow-md
            "
        >
            <div
                className="
                    flex
                    items-start
                    justify-between
                "
            >
                <div>
                    <p
                        className="
                            text-sm
                            font-medium
                            text-slate-500
                        "
                    >
                        {title}
                    </p>

                    <div
                        className="
                            mt-3
                            text-4xl
                            font-black
                            text-slate-900
                        "
                    >
                        {value}
                    </div>

                    {subtitle && (
                        <p
                            className="
                                mt-2
                                text-sm
                                text-slate-500
                            "
                        >
                            {subtitle}
                        </p>
                    )}
                </div>

                <div
                    className="
                        rounded-2xl
                        bg-slate-100
                        p-3
                        text-slate-700
                    "
                >
                    {icon}
                </div>
            </div>

            {trend && (
                <div
                    className="
                        mt-6
                        flex
                        items-center
                        gap-2
                        text-sm
                        font-semibold
                        text-emerald-600
                    "
                >
                    <TrendingUp className="h-4 w-4" />

                    {trend}
                </div>
            )}
        </div>
    );
}
