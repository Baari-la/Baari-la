import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm, usePage } from "@inertiajs/react";

export default function Gallery({ auth, galleries }) {
    const { translations } = usePage().props;
    const t = (key) =>
        translations && translations[key] ? translations[key] : key;

    // Form logic untuk Upload
    const { data, setData, post, processing, reset, errors } = useForm({
        title_id: "",
        title_en: "",
        category: "factory_visit",
        image: null,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route("admin.gallery.store"), {
            onSuccess: () => reset(),
        });
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Manage Gallery - DigestexGlobal" />

            <div className="py-12 bg-[#0a192f] min-h-screen text-white">
                <div className="max-w-7xl mx-auto px-6">
                    <h1 className="text-3xl font-black uppercase italic mb-10 tracking-tighter">
                        Manage{" "}
                        <span className="text-emerald-400">Documentation</span>
                    </h1>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-10">
                        {/* FORM UPLOAD */}
                        <div className="bg-white/5 p-8 rounded-[40px] border border-white/10 h-fit sticky top-10">
                            <h3 className="text-white text-xs font-black uppercase tracking-widest mb-6 italic">
                                Upload New Event
                            </h3>
                            <form onSubmit={submit} className="space-y-4">
                                <div>
                                    <input
                                        type="text"
                                        placeholder="Event Title (ID)"
                                        className="w-full bg-[#050c1b] border-white/10 rounded-xl text-xs text-white"
                                        onChange={(e) =>
                                            setData("title_id", e.target.value)
                                        }
                                        value={data.title_id}
                                    />
                                    {errors.title_id && (
                                        <p className="text-red-500 text-[8px] mt-1 uppercase">
                                            {errors.title_id}
                                        </p>
                                    )}
                                </div>

                                <select
                                    className="w-full bg-[#050c1b] border-white/10 rounded-xl text-xs text-white"
                                    onChange={(e) =>
                                        setData("category", e.target.value)
                                    }
                                >
                                    <option value="factory_visit">
                                        Factory Visit
                                    </option>
                                    <option value="seminar">
                                        International Summit
                                    </option>
                                    <option value="government">
                                        Government Briefing
                                    </option>
                                </select>

                                <input
                                    type="file"
                                    className="text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-emerald-600 file:text-white hover:file:bg-emerald-500"
                                    onChange={(e) =>
                                        setData("image", e.target.files[0])
                                    }
                                />

                                <button
                                    disabled={processing}
                                    className="w-full bg-emerald-600 hover:bg-emerald-500 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest transition-all shadow-lg"
                                >
                                    {processing
                                        ? "Uploading..."
                                        : "Publish to Gallery"}
                                </button>
                            </form>
                        </div>

                        {/* LIST FOTO YANG SUDAH DIUPLOAD */}
                        <div className="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            {galleries.map((item) => (
                                <div
                                    key={item.id}
                                    className="bg-white/5 rounded-3xl overflow-hidden border border-white/5 group relative"
                                >
                                    <img
                                        src={`/storage/${item.image_path}`}
                                        className="w-full h-48 object-cover opacity-60 group-hover:opacity-100 transition-all"
                                    />
                                    <div className="p-4">
                                        <p className="text-[8px] text-emerald-400 font-black uppercase tracking-widest mb-1">
                                            {item.category}
                                        </p>
                                        <h4 className="text-white text-xs font-bold uppercase">
                                            {item.title_id}
                                        </h4>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
