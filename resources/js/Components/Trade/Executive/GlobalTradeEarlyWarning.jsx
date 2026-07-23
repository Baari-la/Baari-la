import { usePage } from "@inertiajs/react";
import {
    AlertTriangle,
    AlertCircle,
    ShieldAlert,
    TrendingUp,
    TrendingDown,
} from "lucide-react";

const severityColors = {
    CRITICAL: {
        bg: "bg-red-100",
        text: "text-red-700",
        border: "border-red-200",
        icon: ShieldAlert,
    },

    HIGH: {
        bg: "bg-orange-100",
        text: "text-orange-700",
        border: "border-orange-200",
        icon: AlertTriangle,
    },

    MEDIUM: {
        bg: "bg-yellow-100",
        text: "text-yellow-700",
        border: "border-yellow-200",
        icon: AlertCircle,
    },

    LOW: {
        bg: "bg-slate-100",
        text: "text-slate-700",
        border: "border-slate-200",
        icon: AlertCircle,
    },
};

export default function GlobalTradeEarlyWarning({
    dataPeriod = "January-April 2026",

    summary = {
        critical: 0,
        high: 0,
        medium: 0,
        low: 0,
    },

    executiveSummary = "",

    alerts = [],
}) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    return (
        <div
            className="
                overflow-hidden
                rounded-3xl
                border
                border-slate-200
                bg-white
                shadow-sm
            "
        >
            {/* Header */}

            <div className="border-b border-slate-100 px-6 py-5">
                <h2 className="text-2xl font-bold text-slate-900">
                    {isEn
                        ? "Global Trade Early Warning"
                        : "Peringatan Dini Perdagangan Global"}
                </h2>

                <p className="mt-1 text-sm text-slate-500">
                    {isEn ? "Reporting Period" : "Periode Pelaporan"}:{" "}
                    {dataPeriod}
                </p>

                <p className="mt-4 text-sm leading-7 text-slate-600">
                    {executiveSummary}
                </p>
            </div>

            {/* Summary */}

            <div className="grid gap-4 border-b p-6 md:grid-cols-4">
                {[
                    ["CRITICAL", summary.critical],
                    ["HIGH", summary.high],
                    ["MEDIUM", summary.medium],
                    ["LOW", summary.low],
                ].map(([label, value]) => {
                    const config = severityColors[label];

                    const Icon = config.icon;

                    return (
                        <div
                            key={label}
                            className={`
                                rounded-2xl
                                border
                                p-4
                                ${config.bg}
                                ${config.border}
                            `}
                        >
                            <div className="flex items-center justify-between">
                                <span
                                    className={`
                                        text-sm
                                        font-bold
                                        ${config.text}
                                    `}
                                >
                                    {label}
                                </span>

                                <Icon size={20} className={config.text} />
                            </div>

                            <p
                                className={`
                                    mt-3
                                    text-3xl
                                    font-bold
                                    ${config.text}
                                `}
                            >
                                {value}
                            </p>
                        </div>
                    );
                })}
            </div>

            {/* Alerts */}

            <div className="space-y-4 p-6">
                {alerts.map((alert, index) => {
                    const config = severityColors[alert.severity];

                    const Icon = config.icon;

                    return (
                        <div
                            key={index}
                            className={`
                                rounded-2xl
                                border
                                p-5
                                ${config.border}
                            `}
                        >
                            <div className="flex items-start justify-between">
                                <div>
                                    <div className="flex gap-2">
                                        <span
                                            className={`
                                                rounded-full
                                                px-3
                                                py-1
                                                text-xs
                                                font-bold
                                                ${config.bg}
                                                ${config.text}
                                            `}
                                        >
                                            {alert.severity}
                                        </span>

                                        <span
                                            className="
                                                rounded-full
                                                bg-blue-100
                                                px-3
                                                py-1
                                                text-xs
                                                font-bold
                                                text-blue-700
                                            "
                                        >
                                            {alert.type}
                                        </span>
                                    </div>

                                    <h3
                                        className="
                                            mt-4
                                            text-xl
                                            font-bold
                                            text-slate-900
                                        "
                                    >
                                        {alert.country}
                                    </h3>

                                    <p
                                        className="
                                            mt-2
                                            text-sm
                                            leading-7
                                            text-slate-600
                                        "
                                    >
                                        {alert.message}
                                    </p>

                                    <div
                                        className="
                                            mt-4
                                            rounded-xl
                                            bg-slate-50
                                            p-4
                                        "
                                    >
                                        <p
                                            className="
                                                text-xs
                                                font-semibold
                                                uppercase
                                                tracking-wide
                                                text-slate-500
                                            "
                                        >
                                            {isEn
                                                ? "Recommendation"
                                                : "Rekomendasi"}
                                        </p>

                                        <p
                                            className="
                                                mt-2
                                                text-sm
                                                text-slate-700
                                            "
                                        >
                                            {alert.recommendation}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    className={`
                                        rounded-2xl
                                        p-3
                                        ${config.bg}
                                    `}
                                >
                                    <Icon size={28} className={config.text} />
                                </div>
                            </div>
                        </div>
                    );
                })}

                {alerts.length === 0 && (
                    <div className="py-10 text-center">
                        <TrendingUp
                            size={40}
                            className="
                                mx-auto
                                text-emerald-500
                            "
                        />

                        <p className="mt-4 text-slate-600">
                            {isEn
                                ? "No significant risks or opportunities detected."
                                : "Tidak ada risiko atau peluang signifikan yang terdeteksi."}
                        </p>
                    </div>
                )}
            </div>
        </div>
    );
}
