import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, router, Link } from "@inertiajs/react";
import LocationsSection from "@/Components/Company/LocationsSection";
import Swal from "sweetalert2";
import MachinesSection from "@/Components/Company/MachinesSection";
import CertificationsSection from "@/Components/Company/CertificationsSection";
import LinksSection from "@/Components/Company/LinksSection";
import MoqsSection from "@/Components/Company/MoqsSection";
import ImagesSection from "@/Components/Company/ImagesSection";
import CapacitiesSection from "@/Components/Company/CapacitiesSection";
import ProductsSection from "@/Components/Company/ProductsSection";
import ContactsSection from "@/Components/Company/ContactsSection";
import MarketsSection from "@/Components/Company/MarketsSection";
import LeadTimesSection from "@/Components/Company/LeadTimesSection";

export default function Edit({ auth, company }) {
    const isEn = auth.locale === "en";
    const { data, setData, post, processing, errors } = useForm({
        _method: "post",
        /*

        |--------------------------------------------------------------------------
        | Basic Company Data
        |--------------------------------------------------------------------------
        */
        nama_perusahaan: company.nama_perusahaan || "",
        category: company.category || "", // Ditambahkan agar tidak undefined
        pimpinan: company.pimpinan || "",
        tenaga_kerja: company.tenaga_kerja || "",
        alamat_lengkap: company.alamat_lengkap || "",
        telepon: company.telepon || "",
        email_web: company.email_web || "",
        membership_type: company.membership_type || "public",
        /*

        |--------------------------------------------------------------------------
        | Location Fields
        |--------------------------------------------------------------------------
        */
        city: company.city || "", // Ditambahkan agar tidak undefined
        wilayah: company.wilayah || "", // Ditambahkan agar tidak undefined
        /*

        |--------------------------------------------------------------------------
        | Legacy Fallback Fields
        |--------------------------------------------------------------------------
        */
        produk: company.produk || "",
        pasar_ekspor: company.pasar_ekspor || "",
        /*

        |--------------------------------------------------------------------------
        | Relational Data
        |--------------------------------------------------------------------------
        */
        products: company.products || [],
        markets: company.markets || [],
        certifications: company.certifications || [],
        capacities: company.capacities || [],
        machines: company.machines || [],
        moqs: company.moqs || [],
        lead_times: company.leadTimes || [],

        locations: company.locations || [],

        contacts: company.contacts || [],
        links: company.links || [],
        images: company.images || [],
        /*

        |--------------------------------------------------------------------------
        | Inventory
        |--------------------------------------------------------------------------
        */
        stock_ready_caption: company.stock_ready_caption || "",
        stock_qty: company.stock_qty || 0,
        stock_unit: company.stock_unit || "kg",
        price: company.price || 0,
    });

    const handleSubmit = async (e) => {
        e.preventDefault();

        const result = await Swal.fire({
            icon: "question",
            title: "Submit Changes?",
            html: `
            <div style="text-align:left">
                <p>
                    Your company profile updates will be submitted
                    for verification.
                </p>

                <br/>

                <p>
                    Changes will not appear publicly until approved
                    by an administrator.
                </p>
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: "Submit for Review",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#2563eb",
        });

        if (!result.isConfirmed) {
            return;
        }

        post(route("companies.update", company.id), {
            forceFormData: true,

            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Update Submitted",
                    html: `
                    <div style="text-align:center">
                        <p>
                            Your update request has been submitted
                            successfully.
                        </p>

                        <br/>

                        <p>
                            The changes are now waiting for
                            administrator verification.
                        </p>
                    </div>
                `,
                    confirmButtonText: "OK",
                });
            },

            onError: () => {
                Swal.fire({
                    icon: "error",
                    title: "Submission Failed",
                    text: "Unable to submit your update request.",
                });
            },
        });
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={`Edit - ${company.nama_perusahaan}`} />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-6xl mx-auto px-6">
                    {/* HEADER */}
                    <div className="flex items-center gap-4 mb-10">
                        <div className="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/30">
                            <i className="fas fa-edit text-white"></i>
                        </div>
                        <div>
                            <h1 className="text-3xl font-black uppercase italic tracking-tighter text-white leading-none">
                                Admin{" "}
                                <span className="text-blue-500">
                                    Data Editor
                                </span>
                            </h1>
                            <p className="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em] mt-2">
                                Sedang mengedit: {company.nama_perusahaan}
                            </p>
                        </div>
                    </div>
                    {/* Info */}
                    <div
                        className="
    inline-flex
    items-center
    gap-2
    px-4
    py-2
    rounded-full
    bg-amber-500/10
    border
    border-amber-500/20
"
                    >
                        <span className="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>

                        <span className="text-xs font-bold text-amber-400 uppercase">
                            Changes Require Admin Verification
                        </span>
                    </div>

                    <form
                        onSubmit={handleSubmit}
                        className="bg-white/5 border border-white/10 p-10 rounded-[40px] space-y-10 backdrop-blur-xl"
                    >
                        {/* BUTTON ACTION */}
                        {/* =======================================================
    STICKY SAVE BAR
======================================================= */}

                        <div
                            className="
        sticky
        top-0
        z-50
        mb-6
        bg-slate-900/95
        backdrop-blur-xl
        border
        border-white/10
        rounded-3xl
        p-4
        shadow-2xl
    "
                        >
                            <div className="flex items-center justify-between">
                                <div>
                                    <h3 className="text-sm font-black text-white uppercase tracking-widest">
                                        Company Profile Editor
                                    </h3>

                                    <p className="text-xs text-gray-400">
                                        Save your changes before leaving this
                                        page.
                                    </p>
                                </div>

                                <div className="flex gap-3">
                                    <Link
                                        href={route("companies.index")}
                                        className="
                    px-6
                    py-3
                    border
                    border-white/10
                    rounded-2xl
                    font-black
                    uppercase
                    text-[10px]
                    tracking-widest
                    hover:bg-white/5
                    transition-all
                "
                                    >
                                        Cancel
                                    </Link>

                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="
                    bg-blue-600
                    text-white
                    font-black
                    px-8
                    py-3
                    rounded-2xl
                    uppercase
                    tracking-widest
                    hover:bg-blue-500
                    transition-all
                    shadow-xl
                    shadow-blue-600/30
                "
                                    >
                                        {processing
                                            ? "Updating..."
                                            : "Save Changes"}
                                    </button>
                                </div>
                            </div>
                        </div>
                        {/* Batas Tombol */}
                        {/* BASIC DATA & CEO */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    Category
                                </label>
                                <input
                                    type="text"
                                    value={data.category}
                                    onChange={(e) =>
                                        setData("category", e.target.value)
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>

                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-3">
                                    CEO / Director
                                </label>
                                <input
                                    type="text"
                                    value={data.pimpinan}
                                    onChange={(e) =>
                                        setData("pimpinan", e.target.value)
                                    }
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>
                        </div>

                        {/* Laokasi */}
                        <LocationsSection
                            data={data}
                            setData={setData}
                            company={company}
                        />
                        {/* PRODUCTS RELATIONAL */}
                        <ProductsSection
                            data={data}
                            setData={setData}
                            company={company}
                        />
                        {/* CAPACITY SECTION */}
                        <CapacitiesSection
                            data={data}
                            setData={setData}
                            company={company}
                        />
                        {/* MACHINES */}
                        <MachinesSection
                            data={data}
                            setData={setData}
                            company={company}
                        />
                        {/* MOQ */}
                        <MoqsSection
                            data={data}
                            setData={setData}
                            company={company}
                        />
                        {/* LEAD TIMES */}
                        <LeadTimesSection
                            data={data}
                            setData={setData}
                            company={company}
                        />
                        {/* CONTACTS */}
                        <ContactsSection
                            data={data}
                            setData={setData}
                            company={company}
                        />

                        {/* IMAGES */}
                        <ImagesSection
                            data={data}
                            setData={setData}
                            company={company}
                        />
                        {/* EXPORT MARKETS */}
                        <MarketsSection
                            data={data}
                            setData={setData}
                            company={company}
                        />
                        {/* CERTIFICATIONS */}
                        <CertificationsSection
                            data={data}
                            setData={setData}
                            company={company}
                        />
                        {/* LINKS */}
                        <LinksSection
                            data={data}
                            setData={setData}
                            company={company}
                        />
                        {/* STOCK & INVENTORY */}
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
