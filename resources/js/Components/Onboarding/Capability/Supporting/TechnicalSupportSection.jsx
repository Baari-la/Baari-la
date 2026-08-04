import { Wrench, Settings, GraduationCap, Headphones } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";
import CheckboxCard from "../Shared/CheckboxCard";

export default function TechnicalSupportSection({ data, setData }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    const services = [
        data.installation_service,
        data.commissioning_service,
        data.maintenance_service,
        data.training_service,
        data.after_sales_service,
        data.technical_hotline,
    ].filter(Boolean).length;

    const level =
        services >= 6
            ? "Excellent"
            : services >= 4
              ? "Advanced"
              : services >= 2
                ? "Standard"
                : "Basic";

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <CapabilitySectionTitle
                icon={Wrench}
                title="Technical Support™"
                description="Describe the technical services your company provides before, during, and after product delivery."
            />

            <div className="grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Installation Service"
                    description="Machine or equipment installation."
                    checked={data.installation_service}
                    onChange={(v) => update("installation_service", v)}
                />

                <CheckboxCard
                    title="Commissioning"
                    description="Machine startup and commissioning."
                    checked={data.commissioning_service}
                    onChange={(v) => update("commissioning_service", v)}
                />

                <CheckboxCard
                    title="Preventive Maintenance"
                    description="Scheduled maintenance service."
                    checked={data.maintenance_service}
                    onChange={(v) => update("maintenance_service", v)}
                />

                <CheckboxCard
                    title="Technical Training"
                    description="Operator and engineering training."
                    checked={data.training_service}
                    onChange={(v) => update("training_service", v)}
                />

                <CheckboxCard
                    title="After Sales Support"
                    description="Post-sales technical assistance."
                    checked={data.after_sales_service}
                    onChange={(v) => update("after_sales_service", v)}
                />

                <CheckboxCard
                    title="Technical Hotline"
                    description="Dedicated technical support channel."
                    checked={data.technical_hotline}
                    onChange={(v) => update("technical_hotline", v)}
                />
            </div>

            {/* Technical Support Intelligence */}

            <div className="mt-8 rounded-2xl border border-orange-100 bg-orange-50 p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <div className="font-bold text-orange-700">
                            Technical Support Intelligence™
                        </div>

                        <p className="mt-2 text-sm leading-6 text-slate-600">
                            Technical service capability is one of the most
                            important factors considered by manufacturers when
                            selecting machinery, chemical, accessory, and
                            technology partners.
                        </p>
                    </div>

                    <div className="text-right">
                        <div className="text-3xl font-black text-orange-600">
                            {services}/6
                        </div>

                        <div className="text-sm font-semibold text-slate-500">
                            {level}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
