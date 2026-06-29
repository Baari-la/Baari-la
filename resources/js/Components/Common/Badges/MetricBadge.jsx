import { TrendingUp, TrendingDown } from "lucide-react";

export default function MetricBadge({ value }) {
    const positive = value.startsWith("+");

    return (
        <span
            className={`

                inline-flex

                items-center

                gap-2

                rounded-full

                px-3

                py-1

                text-xs

                font-bold

                ${
                    positive
                        ? "bg-emerald-100 text-emerald-700"
                        : "bg-red-100 text-red-700"
                }

            `}
        >
            {positive ? <TrendingUp size={14} /> : <TrendingDown size={14} />}

            {value}
        </span>
    );
}
