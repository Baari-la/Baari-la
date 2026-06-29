import {
    AlertTriangle,
    TrendingUp,
    TrendingDown,
    ShieldAlert,
} from "lucide-react";

export default function EarlyWarningCard({ alerts = [] }) {
    const getColor = (level) => {
        switch (level) {
            case "high":
                return "border-red-200 bg-red-50 text-red-700";

            case "medium":
                return "border-amber-200 bg-amber-50 text-amber-700";

            default:
                return "border-emerald-200 bg-emerald-50 text-emerald-700";
        }
    };

    const getIcon = (type) => {
        switch (type) {
            case "import":
                return <TrendingDown size={18} />;

            case "export":
                return <TrendingUp size={18} />;

            default:
                return <ShieldAlert size={18} />;
        }
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-slate-100 px-6 py-5">
                <div className="flex items-center gap-2">
                    <AlertTriangle size={18} className="text-red-600" />

                    <h2 className="text-lg font-bold text-slate-900">
                        Early Warning System
                    </h2>
                </div>

                <p className="mt-1 text-sm text-slate-500">
                    EN : Automatic monitoring of abnormal trade movements
                    <br />
                    ID : Pemantauan otomatis terhadap pergerakan perdagangan
                    yang tidak normal.
                </p>
            </div>

            {/* Content */}

            <div className="space-y-4 p-6">
                {alerts.length === 0 && (
                    <div className="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                        <div className="font-semibold text-emerald-700">
                            No significant alerts detected.
                        </div>

                        <div className="mt-1 text-sm text-emerald-600">
                            Tidak ada indikasi lonjakan perdagangan yang
                            memerlukan perhatian.
                        </div>
                    </div>
                )}

                {alerts.map((item, index) => (
                    <div
                        key={index}
                        className={`rounded-2xl border p-4 ${getColor(item.level)}`}
                    >
                        <div className="flex items-start gap-3">
                            {getIcon(item.type)}

                            <div className="flex-1">
                                <div className="font-bold">{item.title}</div>

                                <div className="mt-1 text-sm">
                                    {item.description}
                                </div>

                                {item.change && (
                                    <div className="mt-3 text-xs font-bold">
                                        Change : {item.change}
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
