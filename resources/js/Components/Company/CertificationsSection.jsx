import Swal from "sweetalert2";
import { router } from "@inertiajs/react";

export default function CompanyCertificationsSection({
    data,
    setData,
    company,
}) {
    const updateCertificationField = (index, field, value) => {
        const updated = (data.certifications || []).map((item, i) =>
            i === index
                ? {
                      ...item,
                      [field]: value,
                  }
                : item,
        );

        setData("certifications", updated);
    };

    const removeCertification = (certification, index) => {
        Swal.fire({
            title: "Delete Certification?",
            text: "This certification will be permanently removed.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ef4444",
            cancelButtonColor: "#64748b",
            confirmButtonText: "Yes, Delete",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            if (!certification.id) {
                setData(
                    "certifications",
                    (data.certifications || []).filter((_, i) => i !== index),
                );

                return;
            }

            router.delete(
                route("companies.certifications.destroy", [
                    company.id,
                    certification.id,
                ]),
                {
                    preserveScroll: true,
                    preserveState: true,

                    onSuccess: () => {
                        setData(
                            "certifications",
                            (data.certifications || []).filter(
                                (_, i) => i !== index,
                            ),
                        );

                        Swal.fire({
                            icon: "success",
                            title: "Deleted",
                            text: "Certification deleted successfully.",
                            confirmButtonColor: "#22c55e",
                        });
                    },

                    onError: () => {
                        Swal.fire({
                            icon: "error",
                            title: "Delete Failed",
                            text: "Unable to delete certification.",
                            confirmButtonColor: "#ef4444",
                        });
                    },
                },
            );
        });
    };

    return (
        <div className="pt-6 border-t border-white/5">
            <div className="flex justify-between items-center mb-6">
                <h3 className="text-yellow-400 text-xs font-black uppercase tracking-[0.3em]">
                    Certifications
                </h3>

                <button
                    type="button"
                    onClick={() =>
                        setData("certifications", [
                            ...(data.certifications || []),
                            {
                                id: null,
                                certification_name: "",
                                category: "quality",
                                certification_code: "",
                                issuer: "",
                                certificate_number: "",
                                description: "",

                                certificate_file: null,
                                logo_file: null,

                                logo_url: "",

                                issued_at: "",
                                valid_until: "",

                                status: "active",

                                is_verified: false,
                                is_featured: false,

                                sort_order: 0,
                            },
                        ])
                    }
                    className="bg-yellow-500 text-[#0a192f] px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                >
                    + Add Certification
                </button>
            </div>

            <div className="space-y-6">
                {(data.certifications || []).map((certification, index) => (
                    <div
                        key={certification.id || index}
                        className="bg-white/5 border border-white/10 rounded-3xl p-6 space-y-5"
                    >
                        {/* ROW 1 */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {/* CERTIFICATION NAME */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Certification Name
                                </label>

                                <input
                                    type="text"
                                    value={
                                        certification.certification_name || ""
                                    }
                                    onChange={(e) =>
                                        updateCertificationField(
                                            index,
                                            "certification_name",
                                            e.target.value,
                                        )
                                    }
                                    placeholder="ISO 9001"
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>

                            {/* CATEGORY */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Category
                                </label>

                                <select
                                    value={certification.category || "quality"}
                                    onChange={(e) =>
                                        updateCertificationField(
                                            index,
                                            "category",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full bg-[#0a192f] text-white border border-white/10 rounded-2xl p-3"
                                >
                                    <option value="quality">Quality</option>
                                    <option value="safety">Safety</option>
                                    <option value="environment">
                                        Environment
                                    </option>
                                    <option value="sustainability">
                                        Sustainability
                                    </option>
                                    <option value="security">Security</option>
                                    <option value="textile_compliance">
                                        Textile Compliance
                                    </option>
                                    <option value="social_compliance">
                                        Social Compliance
                                    </option>
                                </select>
                            </div>

                            {/* CODE */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Code
                                </label>

                                <input
                                    type="text"
                                    value={
                                        certification.certification_code || ""
                                    }
                                    onChange={(e) =>
                                        updateCertificationField(
                                            index,
                                            "certification_code",
                                            e.target.value,
                                        )
                                    }
                                    placeholder="ISO9001"
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>
                        </div>

                        {/* ROW 2 */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {/* ISSUER */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Issuer
                                </label>

                                <input
                                    type="text"
                                    value={certification.issuer || ""}
                                    onChange={(e) =>
                                        updateCertificationField(
                                            index,
                                            "issuer",
                                            e.target.value,
                                        )
                                    }
                                    placeholder="OEKO TEX"
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>

                            {/* CERTIFICATE NUMBER */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Certificate Number
                                </label>

                                <input
                                    type="text"
                                    value={
                                        certification.certificate_number || ""
                                    }
                                    onChange={(e) =>
                                        updateCertificationField(
                                            index,
                                            "certificate_number",
                                            e.target.value,
                                        )
                                    }
                                    placeholder="CERT-2026"
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />
                            </div>

                            {/* STATUS */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Status
                                </label>

                                <select
                                    value={certification.status || "active"}
                                    onChange={(e) =>
                                        updateCertificationField(
                                            index,
                                            "status",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full bg-[#0a192f] text-white border border-white/10 rounded-2xl p-3"
                                >
                                    <option value="active">Active</option>
                                    <option value="expired">Expired</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>

                        {/* DESCRIPTION */}
                        <div>
                            <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                Description
                            </label>

                            <textarea
                                rows="4"
                                value={certification.description || ""}
                                onChange={(e) =>
                                    updateCertificationField(
                                        index,
                                        "description",
                                        e.target.value,
                                    )
                                }
                                placeholder="Certification description..."
                                className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-4"
                            />
                        </div>
                        {/* CERTIFICATE FILES */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* CERTIFICATE PDF */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Certificate PDF
                                </label>

                                <input
                                    type="file"
                                    accept=".pdf"
                                    onChange={(e) => {
                                        const file = e.target.files[0];

                                        const updated = [
                                            ...(data.certifications || []),
                                        ];

                                        updated[index].certificate_file = file;

                                        setData("certifications", updated);
                                    }}
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />

                                {/* EXISTING FILE */}
                                {certification.certificate_file &&
                                    typeof certification.certificate_file ===
                                        "string" && (
                                        <a
                                            href={`/storage/${certification.certificate_file}`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="mt-3 inline-flex items-center gap-2 text-xs text-blue-400 hover:text-blue-300 transition-all"
                                        >
                                            <i className="fas fa-file-pdf"></i>
                                            View Uploaded PDF
                                        </a>
                                    )}

                                {/* NEW FILE PREVIEW */}
                                {certification.certificate_file &&
                                    typeof certification.certificate_file !==
                                        "string" && (
                                        <div className="mt-3 text-[11px] text-emerald-400 font-bold">
                                            <i className="fas fa-check-circle mr-2"></i>

                                            {
                                                certification.certificate_file
                                                    .name
                                            }
                                        </div>
                                    )}
                            </div>

                            {/* CERTIFICATION LOGO */}
                            <div>
                                <label className="text-[10px] font-black uppercase text-gray-500 tracking-widest block mb-2">
                                    Certification Logo
                                </label>

                                <input
                                    type="file"
                                    accept="image/*"
                                    onChange={(e) => {
                                        const file = e.target.files[0];

                                        const updated = [
                                            ...(data.certifications || []),
                                        ];

                                        updated[index].logo_file = file;

                                        /*
                |--------------------------------------------------------------------------
                | LIVE PREVIEW
                |--------------------------------------------------------------------------
                */

                                        if (file) {
                                            updated[index].logo_preview =
                                                URL.createObjectURL(file);
                                        }

                                        setData("certifications", updated);
                                    }}
                                    className="w-full bg-white/5 border border-white/10 rounded-2xl text-white p-3"
                                />

                                {/* EXISTING LOGO */}
                                {certification.logo_url &&
                                    !certification.logo_preview && (
                                        <div className="mt-4">
                                            <img
                                                src={`/storage/${certification.logo_url}`}
                                                alt="logo"
                                                className="h-24 object-contain rounded-2xl bg-white p-3 border border-white/10"
                                            />
                                        </div>
                                    )}

                                {/* LIVE PREVIEW */}
                                {certification.logo_preview && (
                                    <div className="mt-4">
                                        <img
                                            src={certification.logo_preview}
                                            alt="preview"
                                            className="h-24 object-contain rounded-2xl bg-white p-3 border border-white/10"
                                        />
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* CHECKBOXES */}
                        <div className="flex flex-wrap gap-6 pt-2">
                            <label className="flex items-center gap-3 text-sm text-white">
                                <input
                                    type="checkbox"
                                    checked={certification.is_verified || false}
                                    onChange={(e) =>
                                        updateCertificationField(
                                            index,
                                            "is_verified",
                                            e.target.checked,
                                        )
                                    }
                                    className="w-4 h-4"
                                />
                                <span>Verified Certification</span>
                            </label>

                            <label className="flex items-center gap-3 text-sm text-white">
                                <input
                                    type="checkbox"
                                    checked={certification.is_featured || false}
                                    onChange={(e) =>
                                        updateCertificationField(
                                            index,
                                            "is_featured",
                                            e.target.checked,
                                        )
                                    }
                                    className="w-4 h-4"
                                />
                                <span>Featured Certification</span>
                            </label>
                        </div>

                        {/* DELETE */}
                        <button
                            type="button"
                            onClick={() =>
                                removeCertification(certification, index)
                            }
                            className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                        >
                            Remove Certification
                        </button>
                    </div>
                ))}
            </div>
        </div>
    );
}
