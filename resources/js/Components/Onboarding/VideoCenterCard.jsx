import { usePage } from "@inertiajs/react";
import { Video, Youtube, Link as LinkIcon, PlayCircle } from "lucide-react";

export default function VideoCenterCard({ data, setData }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const updateField = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div className="mb-8">
                <h2 className="text-2xl font-black text-slate-900">
                    {isEn ? "Video Center" : "Pusat Video"}
                </h2>

                <p className="mt-2 text-sm leading-6 text-slate-500">
                    {isEn
                        ? "Share your corporate and factory videos to help buyers understand your company better."
                        : "Bagikan video perusahaan dan pabrik untuk membantu buyer mengenal perusahaan Anda lebih baik."}
                </p>
            </div>

            <div className="space-y-6">
                {/* Corporate Video */}

                <VideoInput
                    icon={Youtube}
                    title={isEn ? "Corporate Video" : "Video Perusahaan"}
                    placeholder="https://youtube.com/..."
                    value={data.company_video}
                    onChange={(value) => updateField("company_video", value)}
                />

                {/* Factory Tour */}

                <VideoInput
                    icon={Video}
                    title={isEn ? "Factory Tour" : "Tur Pabrik"}
                    placeholder="https://youtube.com/..."
                    value={data.factory_tour || ""}
                    onChange={(value) => updateField("factory_tour", value)}
                />

                {/* Production Process */}

                <VideoInput
                    icon={PlayCircle}
                    title={isEn ? "Production Process" : "Proses Produksi"}
                    placeholder="https://youtube.com/..."
                    value={data.production_process || ""}
                    onChange={(value) =>
                        updateField("production_process", value)
                    }
                />
            </div>

            {/* Recommended Videos */}

            <div className="mt-8 rounded-2xl bg-slate-50 p-5">
                <div className="flex items-center gap-2">
                    <Video className="h-5 w-5 text-indigo-600" />

                    <span className="font-bold text-indigo-700">
                        {isEn
                            ? "Recommended Videos"
                            : "Video yang Direkomendasikan"}
                    </span>
                </div>

                <ul className="mt-4 space-y-2 text-sm text-slate-600">
                    <li>
                        ✓ {isEn ? "Corporate Profile" : "Profil Perusahaan"}
                    </li>

                    <li>✓ {isEn ? "Factory Tour" : "Tur Pabrik"}</li>

                    <li>✓ {isEn ? "Production Process" : "Proses Produksi"}</li>

                    <li>
                        ✓{" "}
                        {isEn
                            ? "Sustainability Program"
                            : "Program Keberlanjutan"}
                    </li>

                    <li>
                        ✓{" "}
                        {isEn
                            ? "Innovation & Technology"
                            : "Inovasi & Teknologi"}
                    </li>

                    <li>✓ {isEn ? "CEO Message" : "Pesan CEO"}</li>
                </ul>
            </div>

            {/* Video Intelligence */}

            <div className="mt-6 rounded-2xl bg-red-50 p-5">
                <div className="flex items-center gap-2">
                    <Youtube className="h-5 w-5 text-red-600" />

                    <span className="font-bold text-red-700">
                        Video Intelligence™
                    </span>
                </div>

                <p className="mt-3 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Professional videos increase buyer confidence, improve Company Visibility Score™, and strengthen Smart Business Matching™."
                        : "Video profesional meningkatkan kepercayaan buyer, memperkuat Company Visibility Score™, dan meningkatkan Smart Business Matching™."}
                </p>
            </div>
        </div>
    );
}

function VideoInput({ icon: Icon, title, value, onChange, placeholder }) {
    return (
        <div>
            <label className="mb-2 block font-semibold">{title}</label>

            <div className="relative">
                <Icon className="absolute left-4 top-3.5 h-5 w-5 text-slate-400" />

                <input
                    type="url"
                    value={value}
                    onChange={(e) => onChange(e.target.value)}
                    placeholder={placeholder}
                    className="
                        w-full
                        rounded-xl
                        border
                        border-slate-300
                        py-3
                        pl-12
                        pr-4
                        focus:border-indigo-500
                        focus:outline-none
                        focus:ring-2
                        focus:ring-indigo-100
                    "
                />
            </div>

            {value && (
                <div className="mt-2 flex items-center gap-2 text-sm text-emerald-600">
                    <LinkIcon className="h-4 w-4" />

                    <span>URL detected</span>
                </div>
            )}
        </div>
    );
}
