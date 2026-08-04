import { Factory } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";
import CheckboxCard from "../Shared/CheckboxCard";

export default function ProductionSection({ data, setData }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <CapabilitySectionTitle
                icon={Factory}
                title="Manufacturing Services™"
                description="Describe the manufacturing services your company provides to brands, buyers, and sourcing teams."
            />

            <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <CheckboxCard
                    title="OEM Manufacturing"
                    description="Produce products using buyer specifications."
                    checked={data.oem}
                    onChange={(v) => update("oem", v)}
                />

                <CheckboxCard
                    title="ODM Manufacturing"
                    description="Provide product development and manufacturing."
                    checked={data.odm}
                    onChange={(v) => update("odm", v)}
                />

                <CheckboxCard
                    title="Private Label"
                    description="Manufacture products under customer brands."
                    checked={data.private_label}
                    onChange={(v) => update("private_label", v)}
                />

                <CheckboxCard
                    title="Full Package Production"
                    description="Manage sourcing, production and delivery."
                    checked={data.full_package}
                    onChange={(v) => update("full_package", v)}
                />

                <CheckboxCard
                    title="CMT Manufacturing"
                    description="Cut, Make and Trim manufacturing services."
                    checked={data.cmt}
                    onChange={(v) => update("cmt", v)}
                />

                <CheckboxCard
                    title="Design Support"
                    description="Support product development and technical design."
                    checked={data.design_support}
                    onChange={(v) => update("design_support", v)}
                />
            </div>

            {/* Manufacturing Intelligence™ */}

            <div className="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-6">
                <div className="font-bold text-indigo-700">
                    Manufacturing Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Manufacturing service capabilities help buyers identify
                    whether your company is suitable for OEM, ODM, private
                    label, full-package sourcing, or specialized production
                    partnerships.
                </p>
            </div>
        </div>
    );
}
