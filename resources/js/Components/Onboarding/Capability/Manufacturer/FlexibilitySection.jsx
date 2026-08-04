import { Zap } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";
import CheckboxCard from "../Shared/CheckboxCard";

export default function FlexibilitySection({ data, setData }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    const score = [
        data.small_batch,
        data.fast_sampling,
        data.quick_response,
        data.custom_product_development,
    ].filter(Boolean).length;

    const readiness =
        score === 4
            ? "Excellent"
            : score >= 3
              ? "Good"
              : score >= 2
                ? "Fair"
                : "Basic";

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <CapabilitySectionTitle
                icon={Zap}
                title="Production Flexibility™"
                description="Show buyers how quickly and flexibly your company can respond to changing market requirements."
            />

            <div className="grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Small Batch Production"
                    description="Accept lower minimum production quantities."
                    checked={data.small_batch}
                    onChange={(v) => update("small_batch", v)}
                />

                <CheckboxCard
                    title="Fast Sampling"
                    description="Provide development samples in a short time."
                    checked={data.fast_sampling}
                    onChange={(v) => update("fast_sampling", v)}
                />

                <CheckboxCard
                    title="Quick Response Manufacturing"
                    description="Respond rapidly to urgent production schedules."
                    checked={data.quick_response}
                    onChange={(v) => update("quick_response", v)}
                />

                <CheckboxCard
                    title="Custom Product Development"
                    description="Develop products based on customer specifications."
                    checked={data.custom_product_development}
                    onChange={(v) => update("custom_product_development", v)}
                />
            </div>

            {/* Flexibility Intelligence */}

            <div className="mt-8 rounded-2xl border border-amber-100 bg-amber-50 p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <div className="font-bold text-amber-700">
                            Flexibility Intelligence™
                        </div>

                        <p className="mt-2 text-sm leading-6 text-slate-600">
                            Production flexibility is an important indicator for
                            brands, sourcing teams, and buyers looking for agile
                            manufacturing partners.
                        </p>
                    </div>

                    <div className="text-right">
                        <div className="text-3xl font-black text-amber-600">
                            {score}/4
                        </div>

                        <div className="text-sm font-semibold text-slate-500">
                            {readiness}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
