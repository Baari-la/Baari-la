/*
|--------------------------------------------------------------------------
| Business Activities Section™
|--------------------------------------------------------------------------
|
| Step 2
|
| This section defines the company's business activities.
| Every change is immediately reflected in the Live
| Classification™ sidebar.
|
|--------------------------------------------------------------------------
*/

import { Factory, Boxes, FlaskConical, BriefcaseBusiness } from "lucide-react";

import CheckboxCard from "../../Shared/CheckboxCard";

export default function BusinessActivitiesSection({ locale, data, setData }) {
    const isEn = locale === "en";

    const update = (field, value) => {
        setData(field, value);
    };

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* -------------------------------------------------------- */}
            {/* Header */}
            {/* -------------------------------------------------------- */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-indigo-100 p-3">
                    <Factory className="h-6 w-6 text-indigo-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        {isEn ? "Business Activities" : "Aktivitas Bisnis"}
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        {isEn
                            ? "Select every business activity operated by your company. DIGESTEX Decision Engine™ will classify your business automatically."
                            : "Pilih seluruh aktivitas bisnis perusahaan. DIGESTEX Decision Engine™ akan melakukan klasifikasi secara otomatis."}
                    </p>
                </div>
            </div>

            {/* =========================================================
                MANUFACTURER
            ========================================================= */}

            <GroupTitle
                icon={Factory}
                title={isEn ? "Manufacturer" : "Manufaktur"}
            />

            <div className="mt-4 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Fiber Producer"
                    description="Staple Fiber, Filament, Synthetic Fiber"
                    checked={data.is_fiber_producer}
                    onChange={(v) => update("is_fiber_producer", v)}
                />

                <CheckboxCard
                    title="Spinner"
                    description="Yarn Manufacturer"
                    checked={data.is_spinner}
                    onChange={(v) => update("is_spinner", v)}
                />

                <CheckboxCard
                    title="Weaving"
                    description="Woven Fabric Manufacturer"
                    checked={data.is_weaving}
                    onChange={(v) => update("is_weaving", v)}
                />

                <CheckboxCard
                    title="Knitting"
                    description="Knitted Fabric Manufacturer"
                    checked={data.is_knitting}
                    onChange={(v) => update("is_knitting", v)}
                />

                <CheckboxCard
                    title="Dyeing & Finishing"
                    description="Wet Processing"
                    checked={data.is_dyeing_finishing}
                    onChange={(v) => update("is_dyeing_finishing", v)}
                />

                <CheckboxCard
                    title="Printing"
                    description="Textile Printing"
                    checked={data.is_printing}
                    onChange={(v) => update("is_printing", v)}
                />

                <CheckboxCard
                    title="Garment"
                    description="Garment Manufacturer"
                    checked={data.is_garment}
                    onChange={(v) => update("is_garment", v)}
                />
            </div>

            {/* =========================================================
                QUALITY
            ========================================================= */}

            <GroupTitle icon={FlaskConical} title="Quality Infrastructure" />

            <div className="mt-4 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Testing Laboratory"
                    description="Textile Testing Services"
                    checked={data.is_testing_laboratory}
                    onChange={(v) => update("is_testing_laboratory", v)}
                />

                <CheckboxCard
                    title="Certification Body"
                    description="Inspection & Certification"
                    checked={data.is_certification_body}
                    onChange={(v) => update("is_certification_body", v)}
                />
            </div>

            {/* =========================================================
                SUPPORTING
            ========================================================= */}

            <GroupTitle icon={Boxes} title="Supporting Industry" />

            <div className="mt-4 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Machinery Supplier"
                    description="Machinery Manufacturer / Distributor"
                    checked={data.is_machinery_supplier}
                    onChange={(v) => update("is_machinery_supplier", v)}
                />

                <CheckboxCard
                    title="Accessories Supplier"
                    description="Buttons, Zipper, Labels, etc."
                    checked={data.is_accessories_supplier}
                    onChange={(v) => update("is_accessories_supplier", v)}
                />

                <CheckboxCard
                    title="Chemical Supplier"
                    description="Textile Chemicals"
                    checked={data.is_chemical_supplier}
                    onChange={(v) => update("is_chemical_supplier", v)}
                />
            </div>

            {/* =========================================================
                COMMERCIAL
            ========================================================= */}

            <GroupTitle icon={BriefcaseBusiness} title="Commercial" />

            <div className="mt-4 grid gap-4 md:grid-cols-2">
                <CheckboxCard
                    title="Trader"
                    description="Trading Company"
                    checked={data.is_trader}
                    onChange={(v) => update("is_trader", v)}
                />

                <CheckboxCard
                    title="Brand Owner"
                    description="Own Brand"
                    checked={data.is_brand}
                    onChange={(v) => update("is_brand", v)}
                />

                <CheckboxCard
                    title="Buying Office"
                    description="Global Sourcing Office"
                    checked={data.is_buying_office}
                    onChange={(v) => update("is_buying_office", v)}
                />
            </div>

            {/* -------------------------------------------------------- */}
            {/* Intelligence */}
            {/* -------------------------------------------------------- */}

            <div className="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">
                <div className="font-bold text-indigo-700">
                    DIGESTEX Decision Engine™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Every business activity you select is analyzed in real time. The Business Intelligence™ panel on the right will automatically determine your Business Category, Industry Type, Value Chain, and Capability Framework."
                        : "Setiap aktivitas bisnis yang dipilih akan dianalisis secara real-time. Panel Business Intelligence™ di sebelah kanan akan secara otomatis menentukan kategori bisnis, jenis industri, posisi value chain, dan capability framework perusahaan."}
                </p>
            </div>
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| Group Title
|--------------------------------------------------------------------------
*/

function GroupTitle({ icon: Icon, title }) {
    return (
        <div className="mt-10 flex items-center gap-3 border-b border-slate-200 pb-3">
            <Icon className="h-5 w-5 text-indigo-600" />

            <h3 className="text-lg font-bold">{title}</h3>
        </div>
    );
}
