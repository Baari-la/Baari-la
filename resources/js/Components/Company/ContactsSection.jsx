import Swal from "sweetalert2";
import { router } from "@inertiajs/react";

export default function CompanyContactsSection({ data, setData, company }) {
    const updateContactField = (index, field, value) => {
        const updated = (data.contacts || []).map((item, i) =>
            i === index
                ? {
                      ...item,
                      [field]: value,
                  }
                : item,
        );

        setData("contacts", updated);
    };

    const removeContact = (contact, index) => {
        Swal.fire({
            title: "Delete Contact?",
            text: "This contact record will be permanently removed.",
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

            /*
        |--------------------------------------------------------------------------
        | UNSAVED RECORD
        |--------------------------------------------------------------------------
        */

            if (!contact.id) {
                setData(
                    "contacts",
                    (data.contacts || []).filter((_, i) => i !== index),
                );

                Swal.fire({
                    icon: "success",
                    title: "Removed",
                    text: "Contact removed from form.",
                    timer: 1500,
                    showConfirmButton: false,
                });

                return;
            }

            /*
        |--------------------------------------------------------------------------
        | DATABASE RECORD
        |--------------------------------------------------------------------------
        */

            router.delete(
                route("companies.contacts.destroy", [company.id, contact.id]),
                {
                    preserveScroll: true,
                    preserveState: true,

                    onSuccess: () => {
                        setData(
                            "contacts",
                            (data.contacts || []).filter((_, i) => i !== index),
                        );

                        Swal.fire({
                            icon: "success",
                            title: "Deleted",
                            text: "Contact deleted successfully.",
                            confirmButtonColor: "#22c55e",
                        });
                    },

                    onError: () => {
                        Swal.fire({
                            icon: "error",
                            title: "Delete Failed",
                            text: "Unable to delete contact.",
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
                <h3 className="text-orange-400 text-xs font-black uppercase tracking-[0.3em]">
                    Company Contacts
                </h3>

                <button
                    type="button"
                    onClick={() =>
                        setData("contacts", [
                            ...(data.contacts || []),
                            {
                                id: null,
                                contact_name: "",
                                position: "",
                                phone: "",
                                email: "",
                            },
                        ])
                    }
                    className="bg-orange-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase"
                >
                    + Add Contact
                </button>
            </div>

            <div className="space-y-6">
                {(data.contacts || []).map((contact, index) => (
                    <div
                        key={contact.id || index}
                        className="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white/5 border border-white/10 rounded-3xl p-6"
                    >
                        {/* CONTACT NAME */}
                        <div>
                            <label className="text-[10px] uppercase tracking-widest text-gray-500 font-black block mb-2">
                                Contact Name
                            </label>

                            <input
                                type="text"
                                value={contact.contact_name || ""}
                                onChange={(e) =>
                                    updateContactField(
                                        index,
                                        "contact_name",
                                        e.target.value,
                                    )
                                }
                                placeholder="John Doe"
                                className="w-full bg-[#0a192f] border border-white/10 rounded-2xl text-white p-3"
                            />
                        </div>

                        {/* POSITION */}
                        <div>
                            <label className="text-[10px] uppercase tracking-widest text-gray-500 font-black block mb-2">
                                Position
                            </label>

                            <input
                                type="text"
                                value={contact.position || ""}
                                onChange={(e) =>
                                    updateContactField(
                                        index,
                                        "position",
                                        e.target.value,
                                    )
                                }
                                placeholder="Export Manager"
                                className="w-full bg-[#0a192f] border border-white/10 rounded-2xl text-white p-3"
                            />
                        </div>

                        {/* PHONE */}
                        <div>
                            <label className="text-[10px] uppercase tracking-widest text-gray-500 font-black block mb-2">
                                Phone
                            </label>

                            <input
                                type="text"
                                value={contact.phone || ""}
                                onChange={(e) =>
                                    updateContactField(
                                        index,
                                        "phone",
                                        e.target.value,
                                    )
                                }
                                placeholder="+62..."
                                className="w-full bg-[#0a192f] border border-white/10 rounded-2xl text-white p-3"
                            />
                        </div>

                        {/* EMAIL */}
                        <div>
                            <label className="text-[10px] uppercase tracking-widest text-gray-500 font-black block mb-2">
                                Email
                            </label>

                            <input
                                type="email"
                                value={contact.email || ""}
                                onChange={(e) =>
                                    updateContactField(
                                        index,
                                        "email",
                                        e.target.value,
                                    )
                                }
                                placeholder="email@company.com"
                                className="w-full bg-[#0a192f] border border-white/10 rounded-2xl text-white p-3"
                            />
                        </div>

                        {/* DELETE */}
                        <div className="md:col-span-2 flex justify-end">
                            <button
                                type="button"
                                onClick={() => removeContact(contact, index)}
                                className="bg-red-500 hover:bg-red-400 text-white px-5 py-3 rounded-2xl text-xs font-black uppercase"
                            >
                                Remove Contact
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
