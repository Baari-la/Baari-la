import { BadgeCheck, ClipboardCheck, Globe } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";

import Input from "../Shared/NumberInput";
import CheckboxCard from "../Shared/CheckboxCard";

export default function CertificationSection({ data, setData }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <CapabilitySectionTitle
                icon={BadgeCheck}
                title="Certification Services™"
                description="Describe your certification, inspection, audit, and technical services available for manufacturers, brands, and buyers."
            />

            <div className="grid gap-6 md:grid-cols-2">
                <Input
                    icon={ClipboardCheck}
                    label="Certification Programs"
                    value={data.certification_programs}
                    onChange={(v) => update("certification_programs", v)}
                    placeholder="OEKO-TEX®, GOTS, RCS, GRS..."
                />

                <Input
                    icon={Globe}
                    label="Countries Served"
                    value={data.countries_served}
                    onChange={(v) => update("countries_served", v)}
                    placeholder="Indonesia, Vietnam, Thailand..."
                />
            </div>

            <div className="mt-8 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Product Certification"
                    description="Certification for textile and apparel products."
                    checked={data.product_certification}
                    onChange={(v) => update("product_certification", v)}
                />

                <CheckboxCard
                    title="Factory Audit"
                    description="Conduct manufacturing and supplier audits."
                    checked={data.factory_audit}
                    onChange={(v) => update("factory_audit", v)}
                />

                <CheckboxCard
                    title="Inspection Service"
                    description="Pre-production, inline and final inspection."
                    checked={data.inspection_service}
                    onChange={(v) => update("inspection_service", v)}
                />

                <CheckboxCard
                    title="Technical Consulting"
                    description="Support compliance and quality improvement."
                    checked={data.technical_consulting}
                    onChange={(v) => update("technical_consulting", v)}
                />

                <CheckboxCard
                    title="Training Service"
                    description="Provide technical and compliance training."
                    checked={data.training_service}
                    onChange={(v) => update("training_service", v)}
                />

                <CheckboxCard
                    title="Buyer Compliance Program"
                    description="Support buyer-specific compliance requirements."
                    checked={data.buyer_compliance_program}
                    onChange={(v) => update("buyer_compliance_program", v)}
                />
            </div>

            {/* Certification Intelligence */}

            <div className="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-6">
                <div className="font-bold text-indigo-700">
                    Certification Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Certification capabilities help manufacturers, exporters,
                    global brands, and sourcing teams identify qualified
                    partners for product certification, factory auditing,
                    inspection services, and technical compliance programs.
                </p>
            </div>
        </div>
    );
}
