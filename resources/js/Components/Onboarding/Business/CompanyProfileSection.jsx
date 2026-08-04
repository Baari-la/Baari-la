import { Building2, Calendar, Users, Factory } from "lucide-react";

import SectionHeader from "../Shared/SectionHeader";
import NumberInput from "../Shared/NumberInput";
import SelectInput from "../Shared/SelectInput";

const LEGAL_ENTITY_OPTIONS = [
    { value: "PT", label: "PT" },
    { value: "PMA", label: "PMA" },
    { value: "CV", label: "CV" },
    { value: "Representative Office", label: "Representative Office" },
    { value: "Sole Proprietorship", label: "Sole Proprietorship" },
    { value: "Others", label: "Others" },
];

const EMPLOYEE_RANGE_OPTIONS = [
    { value: "1-10", label: "1 - 10" },
    { value: "11-50", label: "11 - 50" },
    { value: "51-100", label: "51 - 100" },
    { value: "100–250", label: "100 - 250" },
    { value: "251-500", label: "251 - 500" },
    { value: "501-1000", label: "501 - 1,000" },
    { value: "1000+", label: "More than 1,000" },
];

export default function CompanyProfileSection({ data, setData, isEn = true }) {
    const update = (field, value) => {
        setData(field, value);
    };

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <SectionHeader
                icon={Building2}
                title={isEn ? "Company Profile™" : "Profil Perusahaan™"}
                description={
                    isEn
                        ? "Provide your company's basic profile information. These details are used throughout your Digital Company Passport™ and Company Intelligence™."
                        : "Lengkapi informasi dasar perusahaan. Informasi ini digunakan pada Digital Company Passport™ dan Company Intelligence™."
                }
            />

            <div className="grid gap-6 md:grid-cols-2">
                <NumberInput
                    icon={Calendar}
                    label={isEn ? "Established Year" : "Tahun Berdiri"}
                    value={data.year_established}
                    onChange={(v) => update("year_established", v)}
                    placeholder="1980"
                />

                <SelectInput
                    icon={Building2}
                    label={isEn ? "Legal Entity" : "Badan Hukum"}
                    value={data.legal_entity}
                    onChange={(v) => update("legal_entity", v)}
                    options={LEGAL_ENTITY_OPTIONS}
                    placeholder={
                        isEn ? "Select Legal Entity" : "Pilih Badan Hukum"
                    }
                />

                <SelectInput
                    icon={Users}
                    label={isEn ? "Employee Range" : "Jumlah Karyawan"}
                    value={data.employee_range}
                    onChange={(v) => update("employee_range", v)}
                    options={EMPLOYEE_RANGE_OPTIONS}
                    placeholder={
                        isEn ? "Select Employee Range" : "Pilih Jumlah Karyawan"
                    }
                />

                <NumberInput
                    icon={Factory}
                    label={isEn ? "Factory Count" : "Jumlah Pabrik"}
                    value={data.factory_count}
                    onChange={(v) => update("factory_count", v)}
                    placeholder="1"
                />
            </div>

            {/* Company Profile Intelligence */}

            <div className="mt-10 rounded-2xl border border-sky-100 bg-sky-50 p-6">
                <div className="flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <h3 className="text-lg font-black text-sky-700">
                            Company Profile Intelligence™
                        </h3>

                        <p className="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            {isEn
                                ? "Company profile information helps buyers, investors, government agencies, and business partners understand your organization's scale and operational maturity."
                                : "Informasi profil perusahaan membantu buyer, investor, pemerintah, dan mitra bisnis memahami skala serta tingkat kematangan operasional perusahaan."}
                        </p>
                    </div>

                    <div className="grid gap-2 text-sm">
                        <div>
                            <span className="font-semibold text-slate-600">
                                {isEn ? "Established" : "Berdiri"}:
                            </span>{" "}
                            {data.year_established || "-"}
                        </div>

                        <div>
                            <span className="font-semibold text-slate-600">
                                {isEn ? "Legal Entity" : "Badan Hukum"}:
                            </span>{" "}
                            {data.legal_entity || "-"}
                        </div>

                        <div>
                            <span className="font-semibold text-slate-600">
                                {isEn ? "Employees" : "Karyawan"}:
                            </span>{" "}
                            {data.employee_range || "-"}
                        </div>

                        <div>
                            <span className="font-semibold text-slate-600">
                                {isEn ? "Factories" : "Pabrik"}:
                            </span>{" "}
                            {data.factory_count || "0"}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
