import { ShieldCheck, Award, Globe } from "lucide-react";

import CapabilitySectionTitle from "../CapabilitySectionTitle";
import Input from "../Shared/NumberInput";
import SelectInput from "../Shared/SelectInput";
import CheckboxCard from "../Shared/CheckboxCard";

const ACCREDITATION_BODIES = [
    "KAN Indonesia",
    "IAS",
    "A2LA",
    "DAkkS",
    "UKAS",
    "ANAB",
    "CNAS",
    "Other",
];

export default function AccreditationSection({ data, setData }) {
    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <CapabilitySectionTitle
                icon={ShieldCheck}
                title="Accreditation & Recognition™"
                description="Provide information about your laboratory accreditation and international recognition."
            />

            <div className="grid gap-6 md:grid-cols-2">
                <Input
                    icon={Award}
                    label="Accreditation Standard"
                    value={data.accreditation_standard}
                    onChange={(v) => update("accreditation_standard", v)}
                    placeholder="ISO/IEC 17025"
                />

                <SelectInput
                    icon={ShieldCheck}
                    label="Accreditation Body"
                    value={data.accreditation_body}
                    onChange={(v) => update("accreditation_body", v)}
                    options={ACCREDITATION_BODIES}
                />

                <Input
                    icon={Award}
                    label="Certificate Number"
                    value={data.accreditation_number}
                    onChange={(v) => update("accreditation_number", v)}
                    placeholder="Example : LP-123-IDN"
                />

                <Input
                    icon={Globe}
                    label="Recognition / Mutual Recognition"
                    value={data.international_recognition}
                    onChange={(v) => update("international_recognition", v)}
                    placeholder="ILAC MRA, APAC MRA"
                />
            </div>

            <div className="mt-8 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="ISO/IEC 17025 Accredited"
                    description="Accredited testing laboratory."
                    checked={data.iso17025}
                    onChange={(v) => update("iso17025", v)}
                />

                <CheckboxCard
                    title="Internationally Recognized"
                    description="Accreditation recognized internationally."
                    checked={data.internationally_recognized}
                    onChange={(v) => update("internationally_recognized", v)}
                />

                <CheckboxCard
                    title="Accept Overseas Samples"
                    description="Accept testing samples from international customers."
                    checked={data.accept_overseas_samples}
                    onChange={(v) => update("accept_overseas_samples", v)}
                />

                <CheckboxCard
                    title="Issue English Reports"
                    description="Provide official reports in English."
                    checked={data.english_report}
                    onChange={(v) => update("english_report", v)}
                />
            </div>

            {/* Accreditation Intelligence */}

            <div className="mt-8 rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                <div className="font-bold text-emerald-700">
                    Accreditation Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    Accreditation information increases buyer confidence,
                    supports regulatory compliance, and enables Smart Business
                    Matching™ to identify laboratories recognized for
                    international testing and certification programs.
                </p>
            </div>
        </div>
    );
}
