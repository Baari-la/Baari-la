import Card from "../Layout/Card";

import { TrendingUp } from "lucide-react";

export default function InsightCard({
    title,

    children,
}) {
    return (
        <Card>
            <div className="flex items-center gap-3">
                <div className="rounded-xl bg-blue-100 p-2">
                    <TrendingUp size={18} className="text-blue-600" />
                </div>

                <h3 className="font-bold text-slate-900">{title}</h3>
            </div>

            <div className="mt-5 text-sm leading-7 text-slate-600">
                {children}
            </div>
        </Card>
    );
}
